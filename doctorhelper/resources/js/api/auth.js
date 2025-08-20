import axios from 'axios';

const API_URL = '/api';

export const login = async(credentials) => {
    const res = await axios.post(`${API_URL}/login`, credentials);
    return res.data.token;
};

export const register = async(data) => {
    const res = await axios.post(`${API_URL}/register`, data);
    return res.data;
};

export const getUser = async(token) => {
    const res = await axios.get(`${API_URL}/me`, {
        headers: { Authorization: `Bearer ${token}` },
    });
    return res.data;
};

export const logout = async(token) => {
    await axios.post(`${API_URL}/logout`, {}, {
        headers: { Authorization: `Bearer ${token}` },
    });
};