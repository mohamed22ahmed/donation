<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import '@fortawesome/fontawesome-free/css/all.css';

defineProps({
  medications: Array,
  isModalOpen: Boolean,
});

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

                      <button type="button" @click="showMedicationModel(medication)" class="pl-3 text-red-500 text-lg hover:text-gray-500"><i class="fa-solid fa-eye"></i></button>
                      <button type="button" @click="editMedicationModel(medication)" class="pl-3 text-blue-500 text-lg hover:text-gray-500"><i class="fa-solid fa-pencil"></i></button>
                      <button type="button" @click="deleteMedicationModel(medication)" class="pl-3 text-red-500 text-lg hover:text-gray-500"><i class="fa-solid fa-trash"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
        </div>
    </AuthenticatedLayout>

  <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addNewLabel">Hello Modal</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form>
          <div class="modal-body">
            <div class="form-group">
              <label for="name" class="col-sm-4 control-label">Name</label>
              <input type="text" name="name"
                     placeholder="English Description"
                     class="form-control">
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
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