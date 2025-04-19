<script>
import axios from "axios";

export default {
  name: "Offer",
  props: ['offer'],


  data() {
    return {
      quantity: 0
    };
  },

  mounted() {
    this.calculateQuantity();
  },

  methods: {
    calculateQuantity(){
      this.offer.medications.forEach(medication => {
        this.quantity += medication.pivot.quantity
      })
    },

    orderNow() {
      axios.get(route('dashboard.orderNow', this.offer.id))
          .then(() => {
            this.$emit('afterOrdering');
          })
    },
  },
};
</script>

<template>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Offer</h3>
  </div>
  <div class="card-body">
    <table style="width:100%">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Expiry Date</th>
          <th>Picture</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="medication in offer.medications" :key="medication.id">
          <td>{{ medication.id }}</td>
          <td>{{ medication.name }}</td>
          <td>{{ medication.pivot.quantity }}</td>
          <td>{{ medication.pivot.price }}</td>
          <td>{{medication.expiry_date}}</td>
          <td style="justify-items: center;">
            <img
                :src="medication.medication_img"
                alt="Medication Image"
                class="img-fluid"
                style="height: 100px; width: 120px; object-fit: contain"
            />
          </td>
        </tr>
      </tbody>
    </table>

    <table class="mt-4" style="width:100%">
      <thead>
        <tr>
          <th>Quantity</th>
          <th>Total Price</th>
          <th>Offer Owner</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{ quantity }}</td>
          <td>{{ offer.price }}</td>
          <td>{{ offer.user.name }}</td>
        </tr>
        </tbody>
    </table>
  </div>
  <div class="modal-footer py-2 justify-content-center">
    <button type="button" class="btn btn-primary" @click="orderNow">Order Now</button>
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