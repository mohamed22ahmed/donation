<script>
export default {
  name: "showOrderModal",
  props: ['order'],

  mounted() {
    $('#showOrder').modal('show');
  },

  data() {
    return {
      medications: [],
    };
  },

  beforeUnmount() {
    $('#showOrder').modal('hide');
  },
};
</script>

<template>
  <div id="showOrder" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addNewLabel">Order Details</h5>
          <button type="button" class="btn-close" @click="$emit('close')" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="mb-3">Order ID: #{{ order.id }}</div>
            <div class="mb-3">Price: {{ order.price }}</div>
            <div class="mb-3">Quantity: {{ order.quantity }}</div>
            <div class="mb-3">Status: {{ order.status }}</div>
          </div>
          <div class="row">
            <table>
              <thead>
              <tr>
                <th>Medication</th>
                <th>Price</th>
                <th>Quantity</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="med in order.offer.medications" :key="med.id">
                <td>{{ med.name }}</td>
                <td>{{ med.pivot.price }}</td>
                <td>{{ med.pivot.quantity }}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" @click="$emit('close')">Close</button>
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