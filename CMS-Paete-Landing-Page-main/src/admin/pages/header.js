import { apiGet, apiPatch, apiPost, apiDelete } from "../services.js";
import { ObjectEditor } from "../components/ObjectEditor.js";
import { ArrayEditor } from "../components/ArrayEditor.js";

const API = "../../../api/admin/header.php";

const data = await apiGet(API);

ObjectEditor.mount({
  container: "#card-logo",
  label: "Bank Info & Logo",
  data: data,
  fields: [
    { key: "logoSrc", label: "Logo URL", placeholder: "assets/logo.png" },
    { key: "logoAlt", label: "Logo Alt Text", placeholder: "Bank Logo" },
    { key: "bankName", label: "Bank Title", placeholder: "Rural Bank of Paete" },
  ],
  onSave: (val) => apiPatch(API, "logo", val),
});

ArrayEditor.mount({
  container: "#card-links",
  label: "Navigation Links",
  items: data.navLinks || [],
  fields: [
    { key: "name", label: "Link Text", placeholder: "e.g. About Us" },
    { key: "url", label: "URL", placeholder: "about.html" },
  ],
  onSave: (val, index) => apiPatch(API, "navLinks", val, index),
  onDelete: (index) => apiDelete(API, "navLinks", index),
  onAdd: (item) => apiPost(API, "navLinks", item),
});