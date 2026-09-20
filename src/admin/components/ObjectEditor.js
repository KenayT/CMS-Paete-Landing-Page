import { showToast } from "../utilities.js";
import { escHtml } from "../utilities.js";

export const ObjectEditor = {
  mount({ container, label, data, fields, onSave }) {
    const el = document.querySelector(container);
    if (!el) return;

    const inputsHtml = fields
      .map(
        (f) => `
      <div class="field">
        <label>${escHtml(f.label)}</label>
        <input type="text"
               data-key="${f.key}"
               value="${escHtml(data[f.key] ?? "")}"
               placeholder="${f.placeholder ?? ""}">
      </div>`
      )
      .join("");

    el.innerHTML = `
      <div class="card-header">
        <h2>${escHtml(label)}</h2>
        <span class="badge badge-update">Read / Update</span>
      </div>
      <div class="fields">${inputsHtml}</div>
      <button class="btn btn-primary btn-save">Save ${escHtml(label)}</button>
    `;

    el.querySelector(".btn-save").addEventListener("click", async (e) => {
      const btn = e.currentTarget;
      btn.disabled = true;
      btn.textContent = "Saving...";

      const value = {};
      el.querySelectorAll("input[data-key]").forEach((input) => {
        value[input.dataset.key] = input.value.trim();
      });

      try {
        await onSave(value);
        showToast(`${label} saved!`);
      } catch (err) {
        showToast(err.message, "error");
      } finally {
        btn.disabled = false;
        btn.textContent = `Save ${label}`;
      }
    });
  },
};