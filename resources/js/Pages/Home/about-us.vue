<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  background: String,
  content: String
});
</script>

<template>
  <Head title="About" />
  <GuestLayout :can-login="canLogin" :can-register="canRegister" :background="background">
    <div class="flex justify-end items-center h-screen px-5" style="width: 400px">
      <div class="w-full max-w-md p-8 rounded-md">
        {{ content }}
      </div>
    </div>
  </GuestLayout>
</template>
