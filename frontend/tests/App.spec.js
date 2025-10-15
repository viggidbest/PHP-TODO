import { mount } from "@vue/test-utils";
import App from "../src/App.vue";
import axios from "axios";
import MockAdapter from "axios-mock-adapter";

describe("App.vue TODO App", () => {
  let mock;

  beforeEach(() => {
    mock = new MockAdapter(axios);
  });

  afterEach(() => {
    mock.restore();
  });

  test("renders header and input", () => {
    const wrapper = mount(App);
    expect(wrapper.find("h1").text()).toBe("TODOs");
    expect(wrapper.find("input").exists()).toBe(true);
    expect(wrapper.find("button").text()).toBe("Add");
  });

  test("loads todos on mount", async () => {
    const todosData = [{ id: 1, title: "Test task", done: false }];
    mock.onGet("/api/todos").reply(200, todosData);

    const wrapper = mount(App);
    await new Promise((r) => setTimeout(r, 0));

    const items = wrapper.findAll("li");
    expect(items.length).toBe(1);
    expect(wrapper.text()).toContain("Test task");
  });

  test("adds a new todo", async () => {
    mock
      .onPost("/api/todos")
      .reply(201, { id: 2, title: "New Task", done: false });
    const wrapper = mount(App);
    const input = wrapper.find("input");
    await input.setValue("New Task");
    await wrapper.find("form").trigger("submit.prevent");
    await new Promise((r) => setTimeout(r, 0));

    expect(wrapper.text()).toContain("New Task");
  });

  test("does not add empty todo", async () => {
    const wrapper = mount(App);
    await wrapper.find("form").trigger("submit.prevent");
    expect(wrapper.findAll("li").length).toBe(0);
  });

  test("toggles a todo", async () => {
    const todo = { id: 1, title: "Toggle Task", done: false };
    mock.onGet("/api/todos").reply(200, [todo]);
    mock.onPut(`/api/todos/1`).reply(200, { ...todo, done: true });

    const wrapper = mount(App);
    await new Promise((r) => setTimeout(r, 0));

    const checkbox = wrapper.find('input[type="checkbox"]');
    await checkbox.setChecked(true);
    await new Promise((r) => setTimeout(r, 0));

    const span = wrapper.find("span");
    expect(span.element.style.textDecoration).toBe("line-through");
  });
});
