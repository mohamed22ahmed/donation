<template>
  <div class="star-rating-display">
    <span
        v-for="star in maxStars"
        :key="star"
        :class="{
        'star': true,
        'filled': star <= ratingValue,
        'empty': star > ratingValue
      }"
        :style="star <= ratingValue ? getStarColor() : {}"
    >
      ★
    </span>
  </div>
</template>

<script>
export default {
  props: {
    rating: {
      type: Number,
      required: true
    },
    maxStars: {
      type: Number,
      default: 5
    }
  },
  computed: {
    ratingValue() {
      return Math.round(this.rating);
    }
  },
  methods: {
    getStarColor() {
      if (this.rating < 2) {
        return { color: '#ff4444' };
      } else if (this.rating < 4) {
        return { color: '#ffbb33' };
      } else {
        return { color: '#00C851' };
      }
    }
  }
}
</script>

<style scoped>
.star-rating-display {
  display: inline-flex;
  font-size: 1.5rem;
}

.star {
  margin-right: 2px;
}

.star.empty {
  color: #ddd;
}
</style>