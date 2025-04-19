<script>
export default {
  name: "Rating",
  props: ['rating'],


  data() {
    return {
      rating_id: this.rating.id,
    };
  },

  methods: {
    // getOffer() {
    //   axios.post(route('dashboard.orderThisOffer', this.offerId))
    //       .then((response) => {
    //         this.medications = response.data.data[0].medications
    //       })
    // },
  },
};
</script>

<template>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Rating</h3>
  </div>
  <div class="card-body">
    {{rating}}
  </div>
</div>
</template>