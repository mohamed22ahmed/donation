<script setup>
import {Head, Link, usePage} from '@inertiajs/vue3';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  background: String,
});

const {url} = usePage();

// Normalize URLs to match paths for comparison
const normalizeUrl = (fullUrl) => {
  const parser = new URL(fullUrl, window.location.origin);
  return parser.pathname;
};

const getActiveClass = (routeUrl) => {
  const currentPath = normalizeUrl(url);
  const targetPath = normalizeUrl(routeUrl);
  return currentPath === targetPath ? 'bg-gray-700 text-white' : 'text-gray-700';
};
</script>

<template>
  <Head title="Home Page"/>
  <div class="relative min-h-screen"
       :style="`background-image: url('${background}'); background-size: cover; background-position: center;`">
    <!-- Navbar -->
    <nav class="absolute top-0 left-0 w-full bg-gray-100 shadow-md z-10">
      <div class="max-w-7xl mx-auto flex items-center h-16">
        <!-- Left Links -->
        <div class="flex space-x-4 flex-grow">
          <Link
              :href="route('home')"
              :class="`rounded-md px-3 py-2 ${getActiveClass(route('home'))}`"
              class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white"
          >
            Welcome
          </Link>
          <Link
              :href="route('about')"
              :class="`rounded-md px-3 py-2 ${getActiveClass(route('about'))}`"
              class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white"
          >
            About Us
          </Link>
          <Link
              :href="route('contact')"
              :class="`rounded-md px-3 py-2 ${getActiveClass(route('contact'))}`"
              class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white"
          >
            Contact Us
          </Link>
        </div>

        <!-- Right Links -->
        <div class="flex justify-end space-x-4">
          <Link
              v-if="$page.props.auth.user"
              :href="route('dashboard.index')"
              :class="`rounded-md px-3 py-2 ${getActiveClass(route('dashboard.index'))}`"
              class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white"
          >
            Home
          </Link>
          <template v-else>
            <Link
                v-if="canLogin"
                :href="route('login')"
                :class="`rounded-md px-3 py-2 ${getActiveClass(route('login'))}`"
                class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white">
              Log in
            </Link>
            <Link
                v-if="canRegister"
                :href="route('register')"
                :class="`rounded-md px-3 py-2 ${getActiveClass(route('register'))}`"
                class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white">
              Register
            </Link>
          </template>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="relative flex min-h-screen flex-col items-end justify-center pt-16">
      <div class="flex justify-center h-screen">
        <div class="text-center max-w-md m-auto">
          <p class="text-xl font-bold" style="font-size: 3rem">Welcome to </p><span style="font-size: 4rem"> PharmaBridge </span><p class="text-xl font-bold" style="font-size: 3rem"> community </p>
        </div>
      </div>
    </div>
  </div>
</template> 