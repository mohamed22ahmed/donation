<script>
export default {
  name: "showMedicationModal",
  props: ['medication'],

  mounted() {
    $('#showMedication').modal('show');
  },

  beforeUnmount() {
    $('#showMedication').modal('hide');
  }
};
</script>


<template>
  <div
      id="showMedication"
      class="modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addNewLabel">Medication Details</h5>
          <button
              type="button"
              class="btn-close"
              @click="$emit('close')"
              aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="row h-100">
            <div class="col-md-6">
              <div class="mb-3">Name: {{ medication.name }}</div>
              <div class="mb-3">Type: {{ medication.type }}</div>
              <div class="mb-3">Status: {{ medication.status }}</div>
              <div class="mb-3">Quantity: {{ medication.quantity }}</div>
              <div class="mb-3">Price: {{ medication.price }}</div>
              <div class="mb-3">Total: {{ medication.total }}</div>
              <div class="mb-3">Expiry Date: {{ medication.expiry_date }}</div>
            </div>

            <div class="col-md-6 d-flex">
              <img
                  :src="medication.image"
                  alt="Medication Image"
                  class="img-fluid"
                  style="height: 100%; width: auto; object-fit: contain"
              />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
              type="button"
              class="btn btn-danger"
              @click="$emit('close')"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal-body {
  height: 100%;
  display: flex;
  flex: 1;
  overflow-y: auto;
}
.modal-dialog {
  max-height: 300vh;
}

.modal-content {
  height: 100%;
}

.row {
  flex: 1;
  height: 100%;
}
.col-md-6 {
  display: flex;
  flex-direction: column;
  justify-content: center;
  height: 100%;
}
</style>