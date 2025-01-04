<script setup>
import {Link, usePage} from '@inertiajs/vue3';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  background: String
});

const { url } = usePage();

// Normalize URLs to match paths for comparison
const normalizeUrl = (fullUrl) => {
  const parser = new URL(fullUrl, window.location.origin);
  return parser.pathname;
};

const getActiveClass = (routeUrl) => {
  const currentPath = normalizeUrl(url);
  const targetPath = normalizeUrl(routeUrl);
  return currentPath === targetPath ? 'bg-black/50 ring-black text-white' : '';
};
</script>

<template>
  <div class="relative min-h-screen bg-cover bg-center" :style="`background-image: url('${background}');`">
    <nav class="absolute top-4 left-4 flex space-x-4 text-black z-10">
      <Link
          :href="route('home')"
          :class="getActiveClass(route('home'))"
          class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:bg-black/50"
      >
        Home
      </Link>
      <Link
          :href="route('about')"
          :class="getActiveClass(route('about'))"
          class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:bg-black/50"
      >
        About Us
      </Link>
      <Link
          :href="route('contact')"
          :class="getActiveClass(route('contact'))"
          class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:bg-black/50"
      >
        Contact Us
      </Link>
    </nav>
      <nav class="absolute top-4 right-4 flex space-x-4 text-white z-10">
        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="rounded-md px-3 py-2 text-white bg-black/30 ring-1 ring-transparent transition hover:bg-black/50">
          Dashboard
        </Link>

        <template v-else>
          <Link
              v-if="canLogin"
              :href="route('login')"
              class="rounded-md px-3 py-2 text-white bg-black/30 ring-1 ring-transparent transition hover:bg-black/50"
          >
            Log in
          </Link>
          <Link
              v-if="canRegister"
              :href="route('register')"
              class="rounded-md px-3 py-2 text-white bg-black/30 ring-1 ring-transparent transition hover:bg-black/50"
          >
            Register
          </Link>
        </template>
      </nav>

      <!-- Main Content -->
    <div class="relative flex min-h-screen flex-col items-center justify-center">
      <slot></slot>
    </div>
  </div>
</template>
