require('../bootstrap');
import Vue from "vue";
import VueRouter from "vue-router";
import Dashboard from "../views/Dashboard";
import Account from "../views/Account";
import Users from "../views/admin/Users";
import User from "../views/admin/components/User";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "",
    component: Dashboard,
  },
  {
    path: "/account",
    name: "",
    component: Account,
  },
  {
    path: '/admin/users',
    name: 'USER_OVERVIEW',
    component: Users,
  },
  {
    path: '/admin/users/:id',
    name: 'MANAGE_USER',
    component: User,
    props: true
  }
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes
});

router.beforeEach((to, from, next) => {
  if(to.name.length > 0) {
    if(window.User.hasPermission(to.name)) {
      next();
    } else {
      next('/');
    }
  } else {
    next();
  }
});

export default router;
