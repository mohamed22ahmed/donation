<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import axios from 'axios';
import "@fortawesome/fontawesome-free/css/all.css";
import showOfferModal from "@/Pages/Offers/showOfferModal.vue";
import addOfferModal from "@/Pages/Offers/addOfferModal.vue";

export default {
  components: {
    showOfferModal,
    addOfferModal,
    AuthenticatedLayout,
    Head,
  },

  props: {
    offers: {
      type: Object,
      default: () => ({}),
    },
    medications: Array
  },

  mounted() {
    this.userOffers = this.offers.data;
  },

  data() {
    return {
      isModalOpen: false,
      isCreateModalOpen: false,
      offerId: 0,
      price: 0,
      userOffers: [],
    };
  },

  methods: {
    showMedications(offer) {
      this.offerId = offer.id;
      this.price = '';
      this.isModalOpen = true;
    },

    showOffer(offer) {
      this.offerId = offer.id;
      this.price = offer.price;
      this.isModalOpen = true;
    },

    closeModal() {
      this.getOffers();
      this.isModalOpen = false;
      this.isCreateModalOpen = false;
      this.selectedMedication = [];
      this.price = 0;
    },

    getOffers(){
      axios.get(route('offers.getOffers'))
          .then((response) => {
            this.userOffers = response.data.data;
          });
    },

    addOfferModal() {
      this.isModalOpen = false;
      this.selectedMedication = [];
      this.price = 0;
      this.isCreateModalOpen = true;
    },

    deleteOffer(id) {
      axios.get(route('offers.delete', id))
          .then(() => {
            this.getOffers();
          });
    }
  }
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
              @click="addOfferModal()"
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
          <tr v-for="offer in userOffers" :key="offer.id">
            <td>{{ offer.id }}</td>
            <td>
              <button
                  type="button"
                  class="pl-3 text-green-500 text-lg hover:text-gray-500"
                  @click="showMedications(offer)"
              >
                <i class="fa-solid fa-eye"></i>
              </button>
            </td>
            <td>{{ offer.price }}</td>

            <td>
              <button
                  type="button"
                  class="pl-3 text-green-500 text-lg hover:text-gray-500"
                  @click="showOffer(offer)"
              >
                <i class="fa-solid fa-eye"></i>
              </button>
              <button
                  type="button"
                  class="pl-3 text-red-500 text-lg hover:text-gray-500"
                  @click="deleteOffer(offer.id)"
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

  <showOfferModal
      v-if="isModalOpen"
      :offerId="offerId"
      :price="price"
      @close="closeModal"
  />

  <addOfferModal
      v-if="isCreateModalOpen"
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