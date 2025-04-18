<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Offer from '@/Components/Offer.vue';
</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Home
            </h2>
        </template>

        <div class="py-12">
          <div class="row p-5">
            <div class="col-md-4">
              <Offer />
            </div>
            <div class="col-md-4">
              <Offer />
            </div>
            <div class="col-md-4">
              <Offer />
            </div>
          </div>
        </div>
    </AuthenticatedLayout>
</template>
