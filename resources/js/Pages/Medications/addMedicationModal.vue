<script>
export default {
  name: "addMedicationModal",
  props: ['medication'],

  data() {
    return {
      types: [
        'tablet',
        'drug',
        'injection'
      ],
      form: {
        name: '',
        price: '',
        quantity: '',
        type: 'tablet',
      }
    }
  },

  mounted() {
    $('#addMedication').modal('show');
  },

  beforeUnmount() {
    $('#addMedication').modal('hide');
  },

  methods: {
    handleFileUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.form.image = file;
      }
    },

    createMedication() {
      const formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }

      this.$inertia.post(route('medications.store'), formData, {
        preserveState: true,
      });

      this.$emit('created')
      this.$emit('close');
    }
  }
};
</script>

<template>
  <div
      id="addMedication"
      class="modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-light">
          <h5 class="modal-title" id="addNewLabel">Add Medication</h5>
          <button
              type="button"
              class="btn-close"
              @click="$emit('close')"
              aria-label="Close"
          >x</button>
        </div>

        <div class="modal-body">
          <form @submit.prevent="createMedication()" enctype="multipart/form-data">
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="name" class="form-label">Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    name="name"
                    id="name"
                    placeholder="Medication Name"
                    class="form-control"
                    required
                />
              </div>
              <div class="col-md-6">
                <label for="price" class="form-label">Price</label>
                <input
                    v-model="form.price"
                    type="number"
                    name="price"
                    id="price"
                    placeholder="Medication Price"
                    class="form-control"
                    required
                />
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="quantity" class="form-label">Quantity</label>
                <input
                    v-model="form.quantity"
                    type="number"
                    name="quantity"
                    id="quantity"
                    placeholder="Medication Quantity"
                    class="form-control"
                    required
                />
              </div>
              <div class="col-md-6">
                <label for="type" class="form-label">Type</label>
                <select
                    v-model="form.type"
                    name="type"
                    id="type"
                    class="form-select"
                    required
                >
                  <option v-for="type in types" :value="type" :key="type">{{ type }}</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-12">
                <label for="image" class="form-label">Upload Image</label>
                <input
                    type="file"
                    name="image"
                    id="image"
                    class="form-control"
                    @change="handleFileUpload"
                />
              </div>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer border-top-0">
              <button type="button" class="btn btn-danger" @click="$emit('close')">Close</button>
              <button type="submit" class="btn btn-primary">Create</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal-content {
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.modal-header {
  border-bottom: 1px solid #e9ecef;
}

.modal-footer {
  border-top: 1px solid #e9ecef;
}

.form-label {
  font-weight: 500;
  color: #495057;
}

.form-control, .form-select {
  border-radius: 5px;
  border: 1px solid #ced4da;
  transition: border-color 0.3s ease;
}

.form-control:focus, .form-select:focus {
  border-color: #80bdff;
  box-shadow: 0 0 5px rgba(128, 189, 255, 0.5);
}

.btn-close {
  background: transparent;
  border: none;
  font-size: 1.2rem;
}

.btn-close:hover {
  opacity: 0.8;
}
</style>