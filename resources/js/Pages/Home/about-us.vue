<script setup>
import {Head, Link, usePage} from '@inertiajs/vue3';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  background: String,
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
  return currentPath === targetPath ? 'bg-gray-700 text-white' : 'text-gray-700';
};
</script>

<template>
  <Head title="About Us" />
  <div class="relative min-h-screen" :style="`background-image: url('${background}'); background-size: cover; background-position: center;`">
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
            Home
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
              :href="route('dashboard')"
              :class="`rounded-md px-3 py-2 ${getActiveClass(route('dashboard'))}`"
              class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white"
          >
            Dashboard
          </Link>
          <template v-else>
            <Link
                v-if="canLogin"
                :href="route('login')"
                :class="`rounded-md px-3 py-2 ${getActiveClass(route('login'))}`"
                class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white"            >
              Log in
            </Link>
            <Link
                v-if="canRegister"
                :href="route('register')"
                :class="`rounded-md px-3 py-2 ${getActiveClass(route('register'))}`"
                class="ring-1 ring-transparent transition hover:bg-gray-700 hover:text-white"            >
              Register
            </Link>
          </template>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="relative flex min-h-screen flex-col items-center justify-center pt-16">
      <div class="flex justify-center h-screen">
        <div class="w-full max-w-md rounded-md">
          <h1 class="text-2xl font-bold mb-4 text-center pt-12">About Us</h1>

          <p>Welcome to <span class="font-bold">PharmaBridge</span>, the website designed to improve access to medications and reduce medical waste.
          Our platform is created to streamline the collection and redistribution of surplus medications, addressing two critical issues: medical waste and healthcare accessibility.
          </p><br><p>
          We are a group of students dedicated to providing innovative digital solutions aimed at improving communication and facilitating access to medications.</p><p>
          We work to connect donors who have unused, unexpired medications with individuals in need, contributing to providing essential medications at significantly reduced costs.</p><p>
          We aim to reduce the financial burden many individuals face when searching for necessary medications.</p><br>

          <h2 class="text-xl font-bold mb-4 pt-3">Our main goals :</h2>

          <p>- Reducing medical waste.</p>
          <p>- Enhancing healthcare access.</p>

          <p>By registering on the platform, donors can list available medications, and recipients can browse these listings and submit requests.</p>
          <p>The system will match donors with recipients based on location and medication needs, with automated responses to provide immediate assistance.</p>
          <br>

          <p>Thank you for visiting PharmaBridge.</p>
          <p class="pb-3">We are at your service.</p>
        </div>
      </div>
    </div>
  </div>
</template>