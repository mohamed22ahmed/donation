<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@fortawesome/fontawesome-free/css/all.css";
import ShowMedicationModal from "@/Pages/Medications/showMedicationModal.vue";
export default {
  components: {
    AuthenticatedLayout,
    Head,
    ShowMedicationModal,
  },

  props: {
    medications: Array,
  },

  data() {
    return {
      selectedMedication: {},
      isModalOpen: false,
    };
  },

  methods: {
    showMedicationModal(medication) {
      this.selectedMedication = medication;
      this.isModalOpen = true;
    },

    closeModal() {
      this.isModalOpen = false;
      this.selectedMedication = {};
    },

    editMedicationModal(medication) {
      console.log("Edit medication:", medication);
    },

    deleteMedicationModal(medication) {
      console.log("Delete medication:", medication);
    },
  },
};
</script>

<template>
  <Head title="Medications" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Medications List
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <table>
          <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
            <th>Type</th>
            <th>Image</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="medication in medications" :key="medication.id">
            <td>{{ medication.id }}</td>
            <td>{{ medication.name }}</td>
            <td>{{ medication.quantity }}</td>
            <td>{{ medication.price }}</td>
            <td>{{ medication.total }}</td>
            <td>{{ medication.type }}</td>
            <td>{{ medication.image }}</td>
            <td>
              <button
                  type="button"
                  @click="showMedicationModal(medication)"
                  class="pl-3 text-red-500 text-lg hover:text-gray-500"
              >
                <i class="fa-solid fa-eye"></i>
              </button>
              <button
                  type="button"
                  @click="editMedicationModal(medication)"
                  class="pl-3 text-blue-500 text-lg hover:text-gray-500"
              >
                <i class="fa-solid fa-pencil"></i>
              </button>
              <button
                  type="button"
                  @click="deleteMedicationModal(medication)"
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

  <ShowMedicationModal
      v-if="isModalOpen"
      :medication="selectedMedication"
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

