<template>
  <div>
    <b-alert fade :show="emailSent" dismissible @dismissed="emailSent = false">
      An email has been sent to {{ form.name }} @ {{ form.email }}
    </b-alert>
    <b-form @submit.prevent="onSubmit">
      <b-form-group label="New Faculty's Name:" label-for="name">
        <b-form-input
          id="name"
          v-model="form.name"
          placeholder="Enter name"
          required
        ></b-form-input>
      </b-form-group>

      <b-form-group
        label="New Faculty's Email address:"
        label-for="email"
        description="They'll receive an invitation to register at this address"
      >
        <b-form-input
          id="email"
          v-model="form.email"
          type="email"
          placeholder="Enter email"
          required
        ></b-form-input>
      </b-form-group>

      <b-form-group label="Enter faculty's department:" label-for="department">
        <b-form-select v-model="form.department" :options="departmentOptions" />
      </b-form-group>

      <div class="ml-auto">
        <b-button type="submit" variant="primary">Submit</b-button>
      </div>
    </b-form>
  </div>
</template>

<script lang="ts">
import Vue from "vue";
import { mapActions, mapGetters } from "vuex";

export default Vue.extend({
  name: "Invite",
  data() {
    return {
      form: {
        name: "",
        email: "",
        department: "",
      },
      emailSent: false,
    };
  },
  computed: {
    ...mapGetters(["departmentOptions"]),
  },
  mounted() {
    this.fetchDepartments();
  },
  methods: {
    ...mapActions(["fetchDepartments", "sendInvite"]),
    onSubmit() {
      this.sendInvite(this.form)
        .then(() => (this.emailSent = true))
        .catch(() => (this.emailSent = false));
    },
  },
});
</script>
