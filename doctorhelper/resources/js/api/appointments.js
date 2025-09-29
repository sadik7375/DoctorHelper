// api/appointments.js

import axios from 'axios';

const BASE_URL = 'http://localhost:8000/api';

export const searchPatientsByPhone = (phone, token) => {
    return axios.get(`${BASE_URL}/patients`, {
        params: { search: phone },
        headers: {
            Authorization: `Bearer ${token}`,
            Accept: 'application/json',
        },
    });
};

export const createAppointment = (data, token) => {
    if (!token) throw new Error('No auth token found');
    return axios.post(`${BASE_URL}/appointments`, data, {
        headers: {
            Authorization: `Bearer ${token}`,
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    });
};