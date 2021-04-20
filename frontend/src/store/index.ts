import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";

import { AuthResponse } from "@/api/definitions";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    authData: { token: "" },
    errorMsg: "",
    departments: [],
    grants: [],
  },
  getters: {
    errorMsg: (state) => {
      return state.errorMsg;
    },
    authenticated: (state) => {
      return !!state.authData?.token;
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
    grants: (state) => {
      return state.grants;
    },
    grantOptions:(state)=>{
      return state.grants.map((grant: { name: string; type: string }) => {
        return {
          value: grant.name,
          text: grant.type,
        };
      });

    },
  },
  mutations: {
    setGrants(state, grants = []){
      state.grants=grants;
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
    async fetchDepartments({ commit }) {
      const { data } = await axios.get("/api/departments");
      commit("setDepartments", data);
    },
    async getGrants({commit}){
      const { data } = await axios.get("/api/grants");
      commit("setGrants", data);
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
