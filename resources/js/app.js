import './bootstrap';
import { createApp, h } from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/vue3'
import AdminDashboardLayout from './Shared/AdminDashboard/AdminDashboardLayout.vue';
import PublicPagesLayout from './Shared/PublicPagesLayout/PublicPagesLayout.vue';
// import UserDashboardLayout from './Shared/UserDashboardLayout/UserDashboardLayout.vue';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
    let page = pages[`./Pages/${name}.vue`];
    if (name.startsWith("Admin/Dashboard/")) {
      page.default.layout = AdminDashboardLayout;
    }
    if (name.startsWith("Public/") && !name.startsWith("Public/Calender")) {
      if (name.startsWith("Public/UserDashboard/")) {
        page.default.layout = UserDashboardLayout;
      } else {
        page.default.layout = PublicPagesLayout;
      }
    }
    return page;
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .component("Link", Link)
      .component("Head", Head)
      .mount(el)
  },
})