<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@fortawesome/fontawesome-free/css/all.css";

export default {
  components: {
    AuthenticatedLayout,
    Head,
  },

  props: {
    offers: Array
  },
};
</script>

<template>
  <Head title="Offers" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        My Offers
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <div class="float-right mb-2">
          <button
              type="button"
              class="pl-3 text-blue-700 text-lg hover:text-gray-500"
              style="font-size: 22px"
          >
            Create Offer
            <i class="fa-solid fa-plus"></i>
          </button>
        </div>
        <table>
          <thead>
          <tr>
            <th>#</th>
            <th>Medications</th>
            <th>Price</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="offer in offers" :key="offer.id">
            <td>{{ offer.id }}</td>
            <td>
              <button
                  type="button"
                  class="pl-3 text-green-500 text-lg hover:text-gray-500"
              >
                <i class="fa-solid fa-eye"></i>
              </button>
            </td>
            <td>{{ offer.price }}</td>

            <td>
              <button
                  type="button"
                  class="pl-3 text-green-500 text-lg hover:text-gray-500"
              >
                <i class="fa-solid fa-eye"></i>
              </button>
              <button
                  type="button"
                  class="pl-3 text-blue-500 text-lg hover:text-gray-500"
              >
                <i class="fa-solid fa-pencil"></i>
              </button>
              <button
                  type="button"
                  class="pl-3 text-red-500 text-lg hover:text-gray-500"
              >
                <i class="fa-solid fa-trash"></i>
              </button>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}

th {
  background-color: #4a5568;
  color: white;
}
</style>