const BASE = '/patients';

export const patientRoutes = {
    list: BASE,
    create: `${BASE}/create`,
    store: BASE,
    show: (id) => `${BASE}/${id}`,
    delete: (id) => `${BASE}/${id}`,
};