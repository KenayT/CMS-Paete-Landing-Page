import { escHtml, showToast } from "../utilities.js";

export const ArrayEditor = {
  mount(config) {
    this.el = document.querySelector(config.container);
    if (!this.el) return;
    this.config = config;
    this.render(config.items || []);
  },

  render(items) {
    const title = this.config.title || this.config.label || "Items";
    const { fields } = this.config;

    const rowsHtml = (items || [])
      .map(
        (item, i) => `
      <div class="link-item" data-index="${i}">
        ${fields
          .map(
            (f) => `
          <input type="text"
                 data-key="${f.key}"
                 value="${escHtml(item[f.key] ?? "")}"
                 placeholder="${f.placeholder ?? ""}">
        `
          )
          .join("")}
        <button class="btn btn-blue btn-update">Save</button>
        <button class="btn btn-ghost btn-delete">Delete</button>
      </div>`
      )
      .join("");

    const addInputsHtml = fields
      .map(
        (f) => `
      <input type="text"
             class="add-input"
             data-key="${f.key}"
             placeholder="${f.placeholder ?? f.label}">`
      )
      .join("");

    this.el.innerHTML = `
      <div class="card-header">
        <h2>${escHtml(title)}</h2>
        <span class="badge badge-crud">Full CRUD</span>
      </div>
      <div class="links-list">${rowsHtml || "<p class='empty'>No items yet.</p>"}</div>
      <div class="add-row">
        ${addInputsHtml}
        <button class="btn btn-green btn-add">+ Add</button>
      </div>
    `;

    this.attachEvents();
  },

  attachEvents() {
    this.el.querySelectorAll(".btn-update").forEach((btn) => {
      btn.addEventListener("click", async (e) => {
        const row = e.currentTarget.closest(".link-item");
        const index = parseInt(row.dataset.index, 10);
        const value = {};
        row.querySelectorAll("input[data-key]").forEach((input) => {
          value[input.dataset.key] = input.value.trim();
        });

        btn.disabled = true;
        btn.textContent = "...";
        try {
          await this.config.onSave(value, index);
          showToast("Saved!");
        } catch (err) {
          showToast(err.message, "error");
        } finally {
          btn.disabled = false;
          btn.textContent = "Save";
        }
      });
    });

    this.el.querySelectorAll(".btn-delete").forEach((btn) => {
      btn.addEventListener("click", async (e) => {
        const row = e.currentTarget.closest(".link-item");
        const index = parseInt(row.dataset.index, 10);
        btn.disabled = true;

        try {
          const res = await this.config.onDelete(index);
          showToast("Deleted!");
          if (res && res.items) {
            this.render(res.items);
          } else {
            row.remove();
          }
        } catch (err) {
          showToast(err.message, "error");
          btn.disabled = false;
        }
      });
    });

    const addBtn = this.el.querySelector(".btn-add");
    if (addBtn) {
      addBtn.addEventListener("click", async (e) => {
        const btn = e.currentTarget;
        const addRow = this.el.querySelector(".add-row");
        const inputs = Array.from(addRow.querySelectorAll(".add-input"));
        const item = {};
        let hasEmpty = false;

        inputs.forEach((input) => {
          const val = input.value.trim();
          if (!val) hasEmpty = true;
          item[input.dataset.key] = val;
        });

        if (hasEmpty) {
          showToast("All fields are required.", "error");
          return;
        }

        btn.disabled = true;
        try {
          const res = await this.config.onAdd(item);
          showToast("Added!");
          
          if (res && res.items) {
            this.render(res.items);
          } else {
            inputs.forEach(i => i.value = "");
            setTimeout(() => window.location.reload(), 300);
          }
        } catch (err) {
          showToast(err.message, "error");
        } finally {
          btn.disabled = false;
        }
      });
    }
  },
};