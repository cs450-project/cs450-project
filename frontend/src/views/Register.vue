<template>
  <div>
    <b-form :class="{ shake: registrationRejected }" @submit="onSubmit">
      <b-form-group label="Your Name:" label-for="name">
        <b-form-input
          id="name"
          v-model="form.name"
          placeholder="Enter name"
          required
        ></b-form-input>
      </b-form-group>

      <b-form-group
        label="Email address:"
        label-for="email"
        description="We'll never share your email with anyone else."
      >
        <b-form-input
          id="email"
          v-model="form.email"
          type="email"
          placeholder="Enter email"
          required
        ></b-form-input>
      </b-form-group>

      <b-form-group label="Password:" label-for="password">
        <Borat :isValid="pwRequirements">
          <template v-slot:input>
            <b-form-input
              id="password"
              v-model="form.password"
              placeholder="Password"
              type="password"
              required
            ></b-form-input>
          </template>
          <template v-slot:invalid-msg>
            Passwords must
            <ul>
              <li v-if="!has1Capital">have at least 1 capital</li>
              <li v-if="!isLongEnough">
                be longer than {{ minPasswordLen }} characters
              </li>
            </ul>
          </template>
        </Borat>
      </b-form-group>

      <b-form-group label="Re-enter yourpassword:" label-for="password-verify">
        <Borat :isValid="passwordsMatch">
          <template v-slot:input>
            <b-form-input
              id="password-verify"
              v-model="verify.password"
              placeholder="Password"
              type="password"
              required
            ></b-form-input>
          </template>
          <template v-slot:invalid-msg> Passwords must match </template>
        </Borat>
      </b-form-group>
      <div class="ml-auto">
        <b-button type="submit" variant="primary">Submit</b-button>
      </div>
    </b-form>
  </div>
</template>

<script lang="ts">
import Vue from "vue";
import axios from "axios";
import Borat from "@/components/BoratValidated.vue";

interface RegistrationResponse {
  registered: boolean;
  error: string;
}

export default Vue.extend({
  name: "Register",
  components: { Borat },
  props: {
    minPasswordLen: {
      type: Number,
      default: 5,
    },
  },
  data() {
    return {
      form: {
        email: "",
        name: "",
        password: "",
      },
      verify: {
        password: "",
      },
      registrationRejected: false,
    };
  },
  computed: {
    pwRequirements(): boolean {
      return this.isLongEnough && this.has1Capital;
    },
    isLongEnough(): boolean {
      return this.form.password.length >= this.minPasswordLen;
    },
    has1Capital(): boolean {
      return this.form.password.match(/[A-Z]/) !== null;
    },
    passwordsMatch(): boolean {
      return this.pwRequirements && this.form.password === this.verify.password;
    },
  },
  methods: {
    onSubmit(event: Event): void {
      event.preventDefault();
      axios.post("/register.php", this.form).then((data) => {
        this.registrationRejected = !data;
      });
    },
  },
});
</script>

<style lang="scss">
@import "@/assets/scss/cs450.scss";
</style>
