require('./bootstrap');
import Vue from "vue";
import store from "./store";
import VueRouter from "vue-router";

// Views
import Dashboard from "./views/Dashboard";
import Reminders from "./views/Reminders";
import ShortUrl from "./views/ShortUrl";
import Account from "./views/Account";
import Youtube from "./views/Youtube";
import Images from "./views/Images";
import Image from "./views/Image";

// Admin views
import Users from "./views/admin/Users";
import User from "./views/admin/components/User";

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
    path: '/images',
    name: 'UPLOAD_IMAGE',
    component: Images
  },
  {
    path: '/images/:id',
    name: 'UPLOAD_IMAGE',
    component: Image,
    props: true,
  },
  {
    path: '/urls',
    name: 'CREATE_SHORT_URL',
    component: ShortUrl
  },
  {
    path: '/reminders',
    name: '',
    component: Reminders
  },
  {
    path: '/youtube',
    name: 'YOUTUBE',
    component: Youtube
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
  if (to.name.length > 0) {
    if (store.state.User.User.hasPermission(to.name)) {
      next();
    } else {
      next('/');
    }
  } else {
    next();
  }
});

export default router;
