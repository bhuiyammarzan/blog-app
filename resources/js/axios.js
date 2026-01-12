// import axios from "axios";

// axios.defaults.withCredentials = true;
// axios.defaults.baseURL = "http://localhost:8000";

// // CSRF token setup
// axios.interceptors.request.use(async (config) => {
//     if (["post", "put", "delete", "patch"].includes(config.method)) {
//         await axios.get("/sanctum/csrf-cookie");
//     }
//     return config;
// });

// export default axios;

import axios from "axios";

const api = axios.create({
    baseURL: "http://localhost:8000",
    withCredentials: true,
    withXSRFToken: true,
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
        "Content-Type": "application/json",
    },
});

// Attach token to every request
// api.interceptors.request.use((config) => {
//     const token = localStorage.getItem("token");
//     if (token) {
//         config.headers.Authorization = `Bearer ${token}`;
//     }
//     return config;
// });

// Handle invalid / expired token globally
// api.interceptors.response.use(
//     (response) => response,
//     (error) => {
//         if (error.response?.status === 401) {
//             localStorage.removeItem("token");
//             localStorage.removeItem("user");
//             window.location.href = "/login";
//         }
//         return Promise.reject(error);
//     }
// );

export default api;
