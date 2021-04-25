<template>
  <div>
    <b-alert fade :show="emailSent" dismissible @dismissed="emailSent = false">
      An email has been sent to {{ form.name }} @ {{ form.email }}
    </b-alert>
    <b-form @submit.prevent="onSubmit">
      <b-form-group label="Current Faculty's Name:" label-for="name">
        <b-form-input
          id="name"
          v-model="form.name"
          placeholder="Enter name"
          required
        ></b-form-input>
      </b-form-group>

      <b-form-group label="Current Faculty's Department:" label-for="department">
        <b-form-select
          id="department"
          v-model="form.department"
          :options="departmentOptions"
        />
      </b-form-group>

      <div class="ml-auto">
        <b-button type="submit" variant="primary">Submit</b-button>
      </div>
    </b-form>
  </div>
</template>
<!--Took from Invite.vue so that I could only enter both the faculty's name and department
-->
<script lang="ts">
import Vue from "vue";
import { mapActions, mapGetters } from "vuex";

export default Vue.extend({
  name: "Invite",
  data() {
    return {
      form: {
        name: "",
        department: "",
      },
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
        /**
         * Do I do something here.
         */
    },
  },
});
</script>
