<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';

defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  },
  background: {
    type: String,
    required: true,
  },
});

function handleImageError() {
  document.getElementById('background')?.classList.add('hidden');
}

// Check the current page to highlight the active link
const { url } = usePage();
const isAboutUsActive = url === route('about');
</script>

<template>
  <Head title="Welcome" />
  <div class="relative min-h-screen bg-cover bg-center" :style="{ backgroundImage: `url(${background})` }">
    <!-- Navigation -->
    <nav class="absolute top-4 left-4 flex space-x-4 text-white z-10">
      <Link
          :href="route('about')"
          :class="{ 'bg-black/50': isAboutUsActive, 'bg-black/30': !isAboutUsActive }"
          class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:bg-black/50"
      >
        About Us
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
    <div class="relative flex min-h-screen flex-col items-center justify-center text-white selection:bg-[#FF2D20] selection:text-white">
      <slot></slot>
    </div>
  </div>
</template>
