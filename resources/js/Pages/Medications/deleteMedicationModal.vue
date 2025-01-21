<script>
export default {
  name: "deleteMedicationModal",
  props: ['medication'],

  data() {
    return {
      form: {
        id: this.medication.id
      }
    }
  },

  mounted() {
    $('#deleteMedication').modal('show');
  },

  beforeUnmount() {
    $('#deleteMedication').modal('hide');
  },

  methods: {
    deleteMedication() {
      this.$inertia.get(route('medications.delete', this.form.id))
        .then(() => {
          this.$emit('close');
          this.showSuccessMessage('Medication deleted successfully!');
        })
        .catch(error => {
          console.error('Error:', error);
          this.showErrorMessage('Failed to delete medication.');
        });
    }
  },
};
</script>

<template>
  <div
      id="deleteMedication"
      class="modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-light">
          <h5 class="modal-title" id="addNewLabel">Delete Medication</h5>
          <button
              type="button"
              class="btn-close"
              @click="$emit('close')"
              aria-label="Close"
          >x</button>
        </div>

        <div class="modal-body">
          <form @submit.prevent="deleteMedication()">
            <div class="row mb-3 text-center">
              <p class="text-xl">Are you sure you want to delete this medication?</p>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 justify-content-center">
              <button type="button" class="btn btn-danger" @click="$emit('close')">Close</button>
              <button type="submit" class="btn btn-success">Confirm</button>
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