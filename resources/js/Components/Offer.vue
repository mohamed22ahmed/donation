<script>
export default {
  name: "Offer",
  props: ['offer'],


  data() {
    return {
      offer_id: 1,
    };
  },

  methods: {
    getOffer() {
      axios.post(route('dashboard.orderThisOffer', this.offerId))
          .then((response) => {
            this.medications = response.data.data[0].medications
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
    <table>
      <thead>
        <tr>
          <th>Medication</th>
          <th>Price</th>
          <th>Quantity</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Medication 1</td>
          <td>$10</td>
          <td>5</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</template>

<style scoped>

</style>