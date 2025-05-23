<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@fortawesome/fontawesome-free/css/all.css";
import showOfferModal from "@/Pages/Offers/showOfferModal.vue";
import showOrderModal from "@/Pages/Orders/showOrderModal.vue";
import StarRatingDisplay from "@/Pages/Ratings/strRatingDisplay.vue";
import axios from "axios";

export default {
  components: {
    showOfferModal,
    showOrderModal,
    AuthenticatedLayout,
    Head,
    StarRatingDisplay
  },

  props: {
    orders: Array
  },

  data() {
    return {
      offerId: -1,
      ordersCollection: [],
      isModalOpen: false,
      isOrderModalOpen: false,
      selectedOrder: {},
      price: 0,
      currentPage: 1,
    }
  },

  mounted() {
    this.getOrders();
  },

  methods: {
    showOffer(offer) {
      this.offerId = offer.id;
      this.price = offer.price;
      this.isModalOpen = true;
    },

    showOrder(order) {
      this.selectedOrder = order;
      this.isOrderModalOpen = true;
    },

    closeModal() {
      if(!this.isOrderModalOpen && !this.isModalOpen)
        this.getOrders();

      this.isModalOpen = false;
      this.isOrderModalOpen = false;
      this.price = 0;
      this.selectedOrder = {};
    },

    getOrders(page = 1){
        this.currentPage = page;
        axios.get('/orders/getOrders?page=' + page)
          .then((response) => {
            this.ordersCollection = response.data;
          });
    },

    cancelOrder(orderId){
      axios.get(route('orders.cancel', orderId))
          .then(() => {
            this.getOrders();
          });
    },

    completeOrder(orderId){
      axios.get(route('orders.complete', orderId))
          .then(() => {
            this.getOrders();
          });
    },

    deleteOrder(orderId){
      axios.get(route('orders.delete', orderId))
          .then(() => {
            this.getOrders();
          });
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
            <th>Offer Owner</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Rating</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="order in ordersCollection.data" :key="order.id">
              <td>{{ order.id }}</td>
              <td>
                <button
                    type="button"
                    class="pl-3 text-green-500 text-lg hover:text-gray-500"
                    @click="showOffer(order.offer)"
                >
                  <i class="fa-solid fa-eye"></i>
                </button>
              </td>
              <td>{{ order.offer.user.name }}</td>
              <td>{{ order.price }}</td>
              <td>{{ order.quantity }}</td>
              <td>{{ order.status }}</td>
              <td>
                <StarRatingDisplay :rating="order.rating[0]?.degree || 0" />
                <span class="text-sm text-gray-500 ml-1">({{ order.rating[0]?.degree || 0 }})</span>
              </td>
              <td>
                <button
                    type="button"
                    class="pl-3 text-green-500 text-lg hover:text-gray-500"
                    @click="showOrder(order)"
                >
                  <i class="fa-solid fa-eye"></i>
                </button>
                <button
                    type="button"
                    class="pl-3 text-orange-400 text-lg hover:text-gray-500"
                    @click="cancelOrder(order.id)"
                >
                  <i class="fa-solid fa-ban"></i>
                </button>
                <button
                    type="button"
                    class="pl-3 text-blue-500 text-lg hover:text-gray-500"
                    @click="completeOrder(order.id)"
                >
                  <i class="fa-solid fa-check"></i>
                </button>
                <button
                    type="button"
                    class="pl-3 text-red-500 text-lg hover:text-gray-500"
                    @click="deleteOrder(order.id)"
                >
                  <i class="fa-solid fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="d-flex justify-content-center pagination-container pb-5 pt-4">
          <button
              v-if="ordersCollection.total > 0"
              v-for="page in ordersCollection.last_page"
              :key="page"
              @click="getOrders(page)"
              class="paginate-buttons"
              :class="{ active: page === currentPage, 'active-page': page === currentPage }"
          >
            {{ page }}
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>

  <showOfferModal
      v-if="isModalOpen"
      :offerId="offerId"
      :price="price"
      @close="closeModal"
  />

  <showOrderModal
      v-if="isOrderModalOpen"
      :order="selectedOrder"
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

.pagination-container {
  display: flex;
  column-gap: 10px;
}
.paginate-buttons {
  height: 40px;
  width: 40px;
  border-radius: 20px;
  cursor: pointer;
  background-color: rgb(242, 242, 242);
  border: 1px solid rgb(217, 217, 217);
  color: black;
}
.paginate-buttons:hover {
  background-color: #d8d8d8;
}
.active-page {
  background-color: #3498db;
  border: 1px solid #3498db;
  color: white;
}
.active-page:hover {
  background-color: #2988c8;
}
</style>