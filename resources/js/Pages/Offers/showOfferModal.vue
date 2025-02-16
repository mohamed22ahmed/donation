<script>
export default {
  name: "showOfferModal",
  props: ['medications', 'price'],

  mounted() {
    $('#showOffer').modal('show');
  },

  beforeUnmount() {
    $('#showOffer').modal('hide');
  }
};
</script>


<template>
  <div
      id="showOffer"
      class="modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addNewLabel" v-if="price == ''">Medications Details</h5>
          <h5 class="modal-title" id="addNewLabel" v-else>Offer Details</h5>
          <button
              type="button"
              class="btn-close"
              @click="$emit('close')"
              aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <table>
            <thead>
            <tr>
              <th>Medication</th>
              <th>Price</th>
              <th>Quantity</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="medication in medications" :key="medication.id">
              <td>{{ medication.name }}</td>
              <td>{{ medication.quantity }}</td>
              <td>{{ medication.price }}</td>
            </tr>
            <tr v-if="price != ''">
              <td>Total Price</td>
              <td colspan="2">{{ price }}</td>
            </tr>
            </tbody>
          </table>
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
  width: 100%;
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