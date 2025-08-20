import axios from 'axios';

const BASE_URL = 'http://localhost:8000/api'; // API URL

export const fetchPatients = (token) => {
    return axios.get(`${BASE_URL}/patients`, {
        headers: { Authorization: `Bearer ${token}` },
    });
};

export const fetchPatientById = (id, token) => {
    return axios.get(`${BASE_URL}/patients/${id}`, {
        headers: { Authorization: `Bearer ${token}` },
    });
};

export const createPatient = (data, token) => {
    return axios.post(`${BASE_URL}/patients`, data, {
        headers: { Authorization: `Bearer ${token}` },
    });
};

export const updatePatient = (id, data, token) => {
    return axios.put(`${BASE_URL}/patients/${id}`, data, {
        headers: { Authorization: `Bearer ${token}` },
    });
};

export const deletePatient = (id, token) => {
    return axios.delete(`${BASE_URL}/patients/${id}`, {
        headers: { Authorization: `Bearer ${token}` },
    });
};