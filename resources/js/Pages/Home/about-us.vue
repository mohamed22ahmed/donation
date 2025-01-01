<script setup>
defineProps({
  background: {
    type: String,
    required: true,
  },
});
</script>

<template>
  <div class=" text-black min-h-screen flex items-center justify-center p-6">
    <div class="max-w-2xl text-center">
      <h1 class="text-4xl font-bold mb-4">About Us</h1>
      <p class="text-xl">
        Welcome to our About Us page. Here you will learn more about our company,
        mission, and values.
      </p>
      <p class="mt-4">
        Our mission is to provide exceptional service and ensure customer satisfaction.
      </p>
    </div>
  </div>
</template>
