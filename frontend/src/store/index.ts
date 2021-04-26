import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";

import { AuthResponse } from "@/api/definitions";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    authData: { token: "", user:{} },
    errorMsg: "",
    grants: [],
    departments: [],
  },
  getters: {
    errorMsg: (state) => {
      return state.errorMsg;
    },
    authenticated: (state) => {
      return !!state.authData?.token;
    },
    grants: (state) => {
      return state.grants;
    },
    departments: (state) => {
      return state.departments;
    },
    departmentOptions: (state) => {
      return state.departments.map((dept: { id: number; name: string }) => {
        return {
          value: dept.id,
          text: dept.name,
        };
      });
    },
    user: (state) => {
    return state.authData?.user;}
  },
  mutations: {
    setGrants(state, grants = []) {
      state.grants = grants;
    },
    setDepartments(state, departments = []) {
      state.departments = departments;
    },
    setAuthData(state, authData) {
      state.authData = authData;

      localStorage.setItem("authData", JSON.stringify(authData));

      axios.defaults.headers.common["Authorization"] = `Bearer ${
        authData?.token ?? ""
      }`;
    },
    clearAuthData() {
      localStorage.removeItem("authData");
      location.reload();
    },
    setError(state, message) {
      state.errorMsg = message;
    },
    clearError(state) {
      state.errorMsg = "";
    },
  },
  actions: {
    authAction({ commit }, { actionName, credentials }) {
      commit("clearError");

      return axios
        .post<AuthResponse>(`/api/auth/${actionName}`, credentials)
        .then(({ data }) => {
          commit("setAuthData", data);
        })
        .catch((error) => {
          const { message: errMsg, code: errCode } = error.response?.data;

          commit("setError", errMsg ?? "Something unexpected happened 😵");
          throw errCode;
        });
    },
    login({ dispatch }, credentials) {
      return dispatch("authAction", {
        actionName: "login",
        credentials,
      });
    },
    logout({ commit }) {
      commit("clearAuthData");
    },
    register({ dispatch }, credentials) {
      return dispatch("authAction", {
        actionName: "register",
        credentials,
      });
    },
    async fetchGrants({ commit }) {
      const { data } = await axios.get("/api/grants");
      commit("setGrants", data);
    },
    async fetchFacultyGrants({ commit}, credentials){
      const { data } = await axios.get("/api/grants/",credentials);
      commit("setGrants", data);
    },
    async fetchDepartments({ commit }) {
      const { data } = await axios.get("/api/departments");
      commit("setDepartments", data);
    },
    sendInvite({ commit }, inviteData) {
      commit("clearError");

      return axios.post("/api/auth/sendinvite", inviteData).catch((error) => {
        const { message: errMsg, code: errCode } = error.response?.data;

        commit("setError", errMsg ?? "Something unexpected happened 😵");
        throw errCode;
      });
    },
  },
});
