<template>
    <div class="auth-container">
        <div class="auth-card">
            <h2>Login</h2>

            <div v-if="authStore.errors" class="error-message">
                <p v-for="(error, field) in authStore.errors" :key="field">
                    {{ Array.isArray(error) ? error[0] : error }}
                </p>
            </div>

            <form @submit.prevent="handleLogin">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        required
                        autocomplete="email"
                        :disabled="authStore.loading"
                        placeholder="Enter your email"
                    />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        :disabled="authStore.loading"
                        placeholder="Enter your password"
                    />
                </div>

                <button
                    type="submit"
                    class="btn-primary"
                    :disabled="authStore.loading"
                >
                    {{ authStore.loading ? "Logging in..." : "Login" }}
                </button>
            </form>

            <p class="auth-link">
                Don't have an account?
                <router-link to="/register">Register here</router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { reactive, onUnmounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const router = useRouter();
const authStore = useAuthStore();

const form = reactive({
    email: "",
    password: "",
});

const handleLogin = async () => {
    authStore.clearErrors();

    const result = await authStore.login(form);

    // localStorage.setItem("token", result.token || "");

    if (result.status) {
        router.push("/dashboard");
    }
};

onUnmounted(() => {
    authStore.clearErrors();
});
</script>

<style scoped>
.auth-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 70vh;
}

.auth-card {
    background: white;
    padding: 2.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 420px;
}

h2 {
    text-align: center;
    margin-bottom: 2rem;
    color: #333;
    font-size: 1.75rem;
}

.form-group {
    margin-bottom: 1.25rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    color: #555;
    font-weight: 500;
    font-size: 0.95rem;
}

input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

input:focus {
    outline: none;
    border-color: #4caf50;
    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
}

input:disabled {
    background-color: #f5f5f5;
    cursor: not-allowed;
}

.btn-primary {
    width: 100%;
    padding: 0.85rem;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    margin-top: 1rem;
    transition: background-color 0.3s;
}

.btn-primary:hover:not(:disabled) {
    background-color: #45a049;
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.error-message {
    background-color: #fee;
    border-left: 4px solid #f44336;
    color: #c33;
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1.5rem;
}

.error-message p {
    margin: 0;
    font-size: 0.9rem;
}

.auth-link {
    text-align: center;
    margin-top: 1.5rem;
    color: #666;
    font-size: 0.95rem;
}

.auth-link a {
    color: #4caf50;
    text-decoration: none;
    font-weight: 500;
}

.auth-link a:hover {
    text-decoration: underline;
}
</style>
