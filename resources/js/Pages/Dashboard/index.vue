<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import axios from 'axios';
import Offer from '@/Components/Offer.vue';
import Rating from "@/Components/Rating.vue";
import StarRatingDisplay from "@/Pages/Ratings/strRatingDisplay.vue";
import "@fortawesome/fontawesome-free/css/all.css";
import showOrderModal from "@/Pages/Orders/showOrderModal.vue";


export default {
  components: {
    showOrderModal,
    StarRatingDisplay,
    Rating,
    Offer,
    AuthenticatedLayout,
    Head
  },

  mounted() {
    this.getOffers()
    this.getRatings()
  },

  data() {
    return {
      offers: {},
      ratings: {},
      currentPage: 1,
      currentRatingPage: 1,
      isOrderModalOpen: false,
      selectedOrder: {},
      search: '',
      sortBy: 'newest',
      sortOptions: [
        { value: 'newest', label: 'Newest' },
        { value: 'oldest', label: 'Oldest' },
        { value: 'price_high', label: 'Price: High to Low' },
        { value: 'price_low', label: 'Price: Low to High' },
      ]
    };
  },

  computed: {
    hasOffers() {
      return this.offers.data && this.offers.data.length > 0;
    }
  },

  methods: {
    getOffers(page = 1) {
      this.currentPage = page;
      axios.get('/dashboard/getOffers', {
            params: {
              search: this.search,
              sort: this.sortBy,
              page: page
            }
          })
          .then((response) => {
            this.offers = response.data;
          });
    },

    getRatings(page = 1){
      this.currentRatingPage = page;
      axios.get('/dashboard/getRatings?page=' + page)
          .then((response) => {
            this.ratings = response.data;
          });
    },

    handleOfferUpdate() {
      this.getOffers()
      this.getRatings()
    },

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
      this.isOrderModalOpen = false;
      this.selectedOrder = {};
      this.getRatings()
    }
  }
};
</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Home
            </h2>
        </template>

        <div class="flex pt-5 row">
        <div class="col-6 px-4 m-auto justify-content-center items-center">
          <form @submit.prevent="getOffers()">
            <div class="flex">
              <input type="text" name="search" v-model="search"
                     class="form-control flex-grow rounded-l-lg p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                     placeholder="Search for an offer">

              <select v-model="sortBy" @change="getOffers()"
                      class="mx-2 border border-gray-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option v-for="option in sortOptions" :value="option.value" :key="option.value">
                  {{ option.label }}
                </option>
              </select>

              <button
                  type="submit"
                  class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-r-lg transition duration-200">
                Search
              </button>
            </div>
          </form>
        </div>
      </div>

        <div class="px-5 pt-5 font-semibold" style="font-size: 30px">Offers</div>

        <div class="pt-2 row d-flex flex-wrap" v-if="hasOffers">
          <div class="col-md-6 p-5 d-flex" v-for="offer in offers.data" :key="offer.id">
            <Offer :offer="offer" class="flex-grow-1" @offer-updated="handleOfferUpdate"/>
          </div>
        </div>

        <div class="px-5 ml-12 pt-4 row d-flex flex-wrap" v-else>
          No offers found
        </div>

        <div class="d-flex justify-content-center pagination-container pb-5">
          <button
              v-if="offers.total > 0"
              v-for="page in offers.last_page"
              :key="page"
              @click="getOffers(page)"
              class="paginate-buttons"
              :class="{ active: page === currentPage, 'active-page': page === currentPage }"
          >
            {{ page }}
          </button>
        </div>

        <div class="pt-2 px-5">
          <div class="font-semibold" style="font-size: 30px">Ratings</div>

            <table>
              <thead>
              <tr>
                <th>#</th>
                <th>Order</th>
                <th>User</th>
                <th>Rating</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="rating in ratings.data" :key="rating.id">
                <td>{{ rating.id }}</td>
                <td>
                  <button
                      type="button"
                      class="pl-3 text-green-500 text-lg hover:text-gray-500"
                      @click="showOrder(rating.order_id)"
                  >
                    <i class="fa-solid fa-eye"></i>
                  </button>
                </td>
                <td>
                  {{ rating.user.name }}
                </td>
                <td>
                  <StarRatingDisplay :rating="rating.degree || 0" />
                  <span class="text-sm text-gray-500 ml-1">({{ rating.degree || 0 }})</span>
                </td>
              </tr>
              </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center pagination-container pb-5 pt-4">
        <button
            v-if="ratings.total > 0"
            v-for="page in ratings.last_page"
            :key="page"
            @click="getRatings(page)"
            class="paginate-buttons"
            :class="{ active: page === currentRatingPage, 'active-page': page === currentRatingPage }"
        >
          {{ page }}
        </button>
      </div>

    </AuthenticatedLayout>

  <showOrderModal
      v-if="isOrderModalOpen"
      :order="selectedOrder"
      @close="closeModal"
  />
</template>

<style>
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