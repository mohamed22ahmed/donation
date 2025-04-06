<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@fortawesome/fontawesome-free/css/all.css";
import showOrderModal from "@/Pages/Orders/showOrderModal.vue";

export default {
  components: {
    showOrderModal,
    AuthenticatedLayout,
    Head,
  },

  props: {
    orders: Array
  },

  data() {
    return {
      offerId: -1,
      isModalOpen: false,
    }
  },

  methods: {
    showOffer(offerId) {
      this.offerId = offerId;
      this.isModalOpen = true;
    },
  },
};
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        My Orders
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <table>
          <thead>
          <tr>
            <th>#</th>
            <th>Offer</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="order in orders" :key="order.id">
            <td>{{ order.id }}</td>
            <td>
              <button
                  type="button"
                  class="pl-3 text-green-500 text-lg hover:text-gray-500"
                  @click="showOffer(order.offer_id)"
              >
                <i class="fa-solid fa-eye"></i>
              </button>
            </td>
            <td>{{ order.price }}</td>
            <td>{{ order.quantity }}</td>
            <td>{{ order.status }}</td>
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

  <showOrderModal
      v-if="isModalOpen"
      :offerId="offerId"
      @close="closeModal"
  />

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