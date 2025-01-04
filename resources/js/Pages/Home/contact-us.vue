<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  background: String,
});
</script>

<template>
  <Head title="Contact Us" />
  <GuestLayout :can-login="canLogin" :can-register="canRegister" :background="background">
    <div>
      <h1>Contact</h1>
    </div>
  </GuestLayout>
</template>
