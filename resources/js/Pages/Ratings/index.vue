<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@fortawesome/fontawesome-free/css/all.css";
import showOrderModal from "@/Pages/Orders/showOrderModal.vue";
import StarRatingDisplay from "@/Pages/Ratings/strRatingDisplay.vue";
import axios from "axios";

export default {
  components: {
    showOrderModal,
    AuthenticatedLayout,
    Head,
    StarRatingDisplay
  },

  props: {
    ratings: Array
  },

  data() {
    return {
      ratingsCollection: [],
      isOrderModalOpen: false,
      selectedOrder: {},
      currentPage: 1,
    }
  },

  mounted() {
    this.getRatings();
  },

  methods: {
    showOrder(order_id) {
      this.getOrder(order_id).then(() => {
        this.isOrderModalOpen = true
      })
    },

    async getOrder(order_id) {
      const response = await axios.get(route('orders.show', order_id))
      this.selectedOrder = response.data
    },

    closeModal() {
      if(!this.isOrderModalOpen)
        this.getRatings();

      this.isOrderModalOpen = false;
      this.selectedOrder = {};
    },

    getRatings(page = 1){
      this.currentPage = page;
      axios.get('/ratings/getRatings?page=' + page)
          .then((response) => {
            this.ratingsCollection = response.data;
          });
    },

    deleteRating(id){
      axios.get(route('ratings.delete', id))
          .then(() => {
            this.getRatings();
          });
    },
  },
};
</script>

<template>
  <Head title="Ratings" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        My Ratings
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <table>
          <thead>
          <tr>
            <th>#</th>
            <th>Order ID</th>
            <th>Rating</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="rating in ratingsCollection.data" :key="rating.id">
              <td>{{ rating.id }}</td>
              <td>
                {{ rating.order_id }}
              </td>
              <td>
                <StarRatingDisplay :rating="rating.degree || 0" />
                <span class="text-sm text-gray-500 ml-1">({{ rating.degree || 0 }})</span>
              </td>
              <td>
                <button
                    type="button"
                    class="pl-3 text-green-500 text-lg hover:text-gray-500"
                    @click="showOrder(rating.order_id)"
                >
                  <i class="fa-solid fa-eye"></i>
                </button>
                <button
                    type="button"
                    class="pl-3 text-red-500 text-lg hover:text-gray-500"
                    @click="deleteRating(rating.id)"
                >
                  <i class="fa-solid fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="d-flex justify-content-center pagination-container pb-5 pt-4">
          <button
              v-if="ratingsCollection.total > 0"
              v-for="page in ratingsCollection.last_page"
              :key="page"
              @click="getRatings(page)"
              class="paginate-buttons"
              :class="{ active: page === currentPage, 'active-page': page === currentPage }"
          >
            {{ page }}
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>

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