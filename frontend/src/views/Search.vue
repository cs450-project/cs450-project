<template>
  <div>
    
    <b-form @submit.prevent="onSubmit">
        <b-form-group label="Current Faculty's User ID:" label-for="id">
         <b-form-input
          id="id"
          v-model="form.id"
          placeholder="Enter The ID of Any Particular Faculty's Grants."
          required
        ></b-form-input>
        </b-form-group>
<!--
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
      </b-form-group> -->

      <div class="ml-auto">
        <b-button type="submit" variant="primary">Search</b-button>
      </div>
    </b-form> 
    <!--
    <b-card-group columns>
    <b-card
      no-body
      v-for="(grant, i) in grants"
      :key="i"
      :border-variant="borderStyle(grant)"
      :header="grant.title"
      header-border-variant="secondary"
    >
      <b-card-body>
        <b-card-text>
          <ul>
            <li>{{ grant.balance | currency }}</li>
            <li>{{ grant.originalAmount | currency }}</li>
          </ul>
        </b-card-text>
      </b-card-body>
      <b-card-footer>
          <small class="text-muted">{{ grant.status }}</small>
      </b-card-footer>
    </b-card>
  </b-card-group> -->
  </div>
</template>
<!--Took from Invite.vue so that I could only enter both the faculty's name and department
-->
<script lang="ts">
import Vue from "vue";
import { mapActions, mapGetters } from "vuex";

type PrefillData = {
  id: number;
  //name: string;
  //email: string;
  //department: number;
  //userDataToken: string;
};

export default Vue.extend({
  name: "Search",
  data() {
    return {
      form: {
        //name: "",
        //department: "",
        id: "",
      },
    };
  },
  computed: {
    ...mapGetters(["departmentOptions", "grants", "users"]),
  },
  mounted() {
    this.fetchDepartments();
  },
  methods: {
    ...mapActions(["fetchDepartments", "fetchFacultyGrants"]),
    onSubmit() {
        this.fetchFacultyGrants(this.form.id);
    },
    /*
    borderStyle(grant) {
        const styles = {
            "PENDING": "warning",
            "APPROVED": "success",
            "DENIED": "danger",
        };

        return styles[grant.status];
    }, */
  },
});
</script>
