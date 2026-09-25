import { apiGet, apiPatch, apiPost, apiDelete } from '../services.js';
import { ObjectEditor } from '../components/ObjectEditor.js';
import { ArrayEditor } from '../components/ArrayEditor.js';

const API = '../../../api/admin/offerings.php';

async function init() {
  const data = await apiGet(API);
  const info = Array.isArray(data) ? data[0] : data;

  // Mount Section Text & Details
  ObjectEditor.mount({
    container: '#card-general',
    label: 'Section Details',
    data: {
      heading: info.heading ?? '',
      description: info.description ?? '',
      badgeText: info.badgeText ?? '',
      image: info.image ?? ''
    },
    fields: [
      { key: 'heading', label: 'Heading' },
      { key: 'description', label: 'Description', type: 'textarea' },
      { key: 'badgeText', label: 'Badge Text' },
      { key: 'image', label: 'Vector Image Path' }
    ],
    onSave: async (val) => {
      for (const [k, v] of Object.entries(val)) {
        await apiPatch(API, k, v);
      }
    }
  });

  // Loans List
  ArrayEditor.mount({
    container: '#card-loans',
    title: 'Loans Offered',
    items: (info.loans || []).map(loan => (typeof loan === 'object' && loan !== null) ? loan : { name: loan }),
    fields: [{ key: 'name', label: 'Loan Name', placeholder: 'Loan Name' }],
    onSave: (val, index) => apiPatch(API, 'loans', val.name, index),
    onDelete: (index) => apiDelete(API, 'loans', index),
    onAdd: (item) => apiPost(API, 'loans', item.name)
  });

  // Deposits List
  ArrayEditor.mount({
    container: '#card-deposits',
    title: 'Deposits Offered',
    items: (info.deposits || []).map(dep => (typeof dep === 'object' && dep !== null) ? dep : { name: dep }),
    fields: [{ key: 'name', label: 'Deposit Name', placeholder: 'Deposit Name' }],
    onSave: (val, index) => apiPatch(API, 'deposits', val.name, index),
    onDelete: (index) => apiDelete(API, 'deposits', index),
    onAdd: (item) => apiPost(API, 'deposits', item.name)
  });
}

init();