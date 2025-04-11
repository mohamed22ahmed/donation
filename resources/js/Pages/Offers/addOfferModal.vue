<script>
export default {
  name: "addOfferModal",

  data() {
    return {
      isAddOfferModalVisible: true,
      isAddMedicationModalVisible: false,

      errorMessage: '',
      medications: [],
      selectedMedication: {},
      editMode: false,
      offerMedications: [],
      validationMessage: '',

      form: {
        offer_id: 0,
        price: 0,
      },
      tempForm: {
        relation_id: 0,
        id: -1,
        name: '',
        quantity: 0,
        price: 0,
      },
    };
  },

  mounted() {
    this.getNewOfferId();
    $(this.$refs.addOfferModal).modal('show');
  },

  methods: {
    getMedications(){
      axios.get(route('offers.getMedications', this.form.offer_id))
          .then((response) => {
            this.medications = response.data
          })
    },

    getNewOfferId() {
      axios.get(route('offers.newId'))
          .then((response) => {
            this.form.offer_id = response.data;
            this.loadOfferMedications();
          })
    },

    addMedication(){
      this.editMode = false;
      this.resetTempForm();
      this.getMedications();
      $(this.$refs.addOfferModal).modal('hide');
      $(this.$refs.addMedicationModal).modal('show');
    },

    closeMedication(){
      this.editMode = false;
      this.loadOfferMedications();
      $(this.$refs.addMedicationModal).modal('hide');
      $(this.$refs.addOfferModal).modal('show');
      this.resetTempForm()
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    },

    loadOfferMedications(){
      axios.get(route('offers.getOfferMedications', this.form.offer_id))
          .then((response) => {
            console.log(response)
            this.offerMedications = response.data.data[0]['medications']
          })
    },

    resetTempForm(){
      this.tempForm.id = -1;
      this.tempForm.name = '';
      this.tempForm.quantity = 0;
      this.tempForm.price = 0;
    },

    getMedicationPriceAndQuantity(id) {
      for (let i = 0; i < this.medications.length; i++) {
        if (this.medications[i].id === id) {
          this.tempForm.id = this.medications[i].id;
          this.tempForm.name = this.medications[i].name;
          this.tempForm.quantity = this.medications[i].quantity;
          this.tempForm.price = this.medications[i].price;
        }
      }
    },

    appendMedication() {
      const formData = new FormData();
      for (const key in this.tempForm) {
        formData.append(key, this.tempForm[key]);
      }
      formData.append('offer_id', this.form.offer_id);

      axios.post(route('offers.saveMedications'), formData)
          .then(() => {
            this.loadOfferMedications();
          })

      this.closeMedication()
    },

    editMedication(medication) {
      this.selectedMedication = medication;
      this.editMode = true;
      this.getMedicationsWhenUpdate(medication.id);
      this.fillTempFormWithMedication(medication);
      $(this.$refs.addMedicationModal).modal('show');
      $(this.$refs.addOfferModal).modal('hide');
    },

    fillTempFormWithMedication(medication){
      this.tempForm.relation_id = medication.relation_id;
      this.tempForm.id = medication.id;
      this.tempForm.name = medication.name;
      this.tempForm.quantity = medication.quantity;
      this.tempForm.price = medication.price;
    },

    getMedicationsWhenUpdate(medication_id) {
      axios.get(route('offers.getMedicationsForUpdate', [this.form.offer_id, medication_id]))
          .then((response) => {
            this.medications = response.data
          })
    },

    updateMedication() {
      const formData = new FormData();
      for (const key in this.tempForm) {
        formData.append(key, this.tempForm[key]);
      }
      formData.append('offer_id', this.form.offer_id);

      axios.post(route('offers.updateMedications'), formData)
          .then(() => {
            this.loadOfferMedications();
          })
      this.closeMedication();
    },

    deleteMedication(id) {
      axios.post(route('offers.deleteOfferMedication', id))
          .then(() => {
            this.loadOfferMedications();
          })
      this.closeMedication();
    },

    closeModal() {
      $(this.$refs.addOfferModal).modal('hide');
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
      this.$emit('close');
    },

    showMedication(medication){
      $(this.$refs.showMedicationModal).modal('show');
      $(this.$refs.addMedicationModal).modal('hide');
      $(this.$refs.addOfferModal).modal('hide');
      this.fillTempFormWithMedication(medication)
    },

    closeShowMedication() {
      this.validationMessage = '';
      $(this.$refs.showMedicationModal).modal('hide');
      $(this.$refs.addMedicationModal).modal('hide');
      $(this.$refs.addOfferModal).modal('show');
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    },

    saveOffer(){
      this.form.price = 0;
      for (let i = 0; i < this.offerMedications.length; i++) {
          this.form.price += this.offerMedications[i].price * this.offerMedications[i].quantity;
      }

      axios.post(route('offers.store'), this.form)
          .then(() => {
            this.closeModal();
          })
    },

    checkQuantity(){
      for (let i = 0; i < this.medications.length; i++) {
        if (this.medications[i].id === this.tempForm.id) {
          if(this.tempForm.quantity > this.medications[i].quantity || this.tempForm.quantity <= 0){
            this.validationMessage = 'Quantity must be between 1 and ' + this.medications[i].quantity
          }else{
            this.validationMessage = ''
          }
        }
      }
    },

    checkPrice(){
      for (let i = 0; i < this.medications.length; i++) {
        if (this.medications[i].id === this.tempForm.id) {
          if(this.tempForm.price < 0){
            this.validationMessage = 'Price must be greater than or equal to 0'
          }else{
            this.validationMessage = ''
          }
        }
      }
    }
  },
};
</script>

<template>
  <div>
    <div ref="addOfferModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-light">
            <h5 class="modal-title" id="addNewLabel">Add Offer</h5>
            <button type="button" class="btn-close" @click="closeModal" aria-label="Close">x</button>
          </div>

          <div class="modal-body">
            <div class="float-right mb-2">
              <button
                  type="button"
                  class="pl-3 text-blue-700 text-lg hover:text-gray-500"
                  style="font-size: 22px"
                  @click="addMedication"
              >
                Add New
                <i class="fa-solid fa-plus"></i>
              </button>
            </div>
            <br />

            <div>
              <table>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="medication in offerMedications" :key="medication.id">
                    <td>{{ medication.id }}</td>
                    <td>{{ medication.name }}</td>
                    <td>{{ medication.quantity }}</td>
                    <td>{{ medication.price }}</td>
                    <td>
                      <button
                          type="button"
                          class="pl-3 text-green-500 text-lg hover:text-gray-500"
                          @click="showMedication(medication)"
                      >
                        <i class="fa-solid fa-eye"></i>
                      </button>
                      <button
                          type="button"
                          class="pl-3 text-blue-500 text-lg hover:text-gray-500"
                          @click="editMedication(medication)"
                      >
                        <i class="fa-solid fa-pencil"></i>
                      </button>
                      <button
                          type="button"
                          class="pl-3 text-red-500 text-lg hover:text-gray-500"
                          @click="deleteMedication(medication.relation_id)"
                      >
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="modal-footer border-top-0">
            <button type="button" class="btn btn-primary" @click="saveOffer">Save</button>
            <button type="button" class="btn btn-danger" @click="closeModal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div ref="addMedicationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-light">
            <h5 class="modal-title" id="addNewLabel" v-if="!editMode">Add Medication</h5>
            <h5 class="modal-title" id="addNewLabel" v-else>Edit Medication</h5>
            <button
                type="button"
                class="btn-close"
                @click="closeMedication"
                aria-label="Close"
            >x</button>
          </div>

          <div class="modal-body">
            <form @submit.prevent="editMode ? updateMedication() : appendMedication()">
              <div class="row mb-2 text-danger text-center justify-content-center" v-if="validationMessage != ''">
                {{ validationMessage }}
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <label for="medication_id" class="form-label">Medication</label>
                  <select
                      v-model="tempForm.id"
                      name="medication_id"
                      id="medication_id"
                      class="form-select"
                      @change="getMedicationPriceAndQuantity(tempForm.id)"
                      required
                  >
                    <option value="-1" disabled :selected="tempForm.id == -1">Select Medication</option>
                    <option v-for="med in medications" :value="med.id" :key="med.id">{{ med.name }}</option>
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="quantity" class="form-label">Quantity</label>
                  <input
                      v-model="tempForm.quantity"
                      type="number"
                      name="quantity"
                      id="quantity"
                      class="form-control"
                      required
                      @input="checkQuantity"
                  />
                </div>
                <div class="col-md-6">
                  <label for="price" class="form-label">Price</label>
                  <input
                      v-model="tempForm.price"
                      type="number"
                      name="price"
                      id="price"
                      class="form-control"
                      required
                      @input="checkPrice"
                  />
                </div>
              </div>

              <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-danger" @click="closeMedication">Close</button>
                <button type="submit" class="btn btn-primary" v-if="tempForm.id !== -1 && validationMessage === '' && editMode == false">Create</button>
                <button type="submit" class="btn btn-success" v-else-if="tempForm.id !== -1 && validationMessage === '' && editMode == true">Update</button>
                <button type="submit" class="btn btn-secondary" disabled v-else>Create</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div ref="showMedicationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addNewLabel">Show Medication</h5>
            <button type="button" class="btn-close" @click="closeShowMedication" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="mb-3">ID: {{ tempForm.id }}</div>
              <div class="mb-3">Name: {{ tempForm.name }}</div>
              <div class="mb-3">Quantity: {{ tempForm.quantity }}</div>
              <div class="mb-3">Price: {{ tempForm.price }}</div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" @click="closeShowMedication">Close</button>
          </div>
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

.btn-close {
  background: transparent;
  border: none;
  font-size: 1.2rem;
}

.btn-close:hover {
  opacity: 0.8;
}
</style>