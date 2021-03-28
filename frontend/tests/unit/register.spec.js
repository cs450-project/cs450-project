import BootstrapVue from "bootstrap-vue";
import { createLocalVue, mount } from "@vue/test-utils";
import Register from "@/views/Register.vue";

const localVue = createLocalVue();
localVue.use(BootstrapVue);

it("exists", () => {
  const wrapper = mount(Register, { localVue });
  expect(wrapper.exists()).toBe(true);
});
