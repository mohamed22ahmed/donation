<template>
  <div class="chat-popup">
    <div class="chat-header" @click="toggleChat">
      <span>Chat with PharmaBridge</span>
      <span>{{ isOpen ? '−' : '+' }}</span>
    </div>
    <div class="chat-body" v-if="isOpen">
      <div class="chat-messages">
        <div v-for="(message, index) in messages" :key="index"
             class="message"
             :class="message.sender === 'user' ? 'user-message' : 'bot-message'">
          {{ message.text }}
        </div>
        <div v-if="isTyping" class="typing-indicator">
          PharmaBridge is typing...
        </div>
      </div>
      <form @submit.prevent="askQuestion" class="chat-input">
        <input
            type="text"
            v-model="question"
            placeholder="Ask a question"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
        >
        <button
            type="submit"
            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600"
        >
          Send
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const isOpen = ref(false);
const question = ref('');
const messages = ref([]);
const isTyping = ref(false);

const toggleChat = () => {
  isOpen.value = !isOpen.value;
};

const askQuestion = async () => {
  if (!question.value.trim()) return;

  messages.value.push({
    sender: 'user',
    text: question.value
  });

  const userQuestion = question.value;
  question.value = '';
  isTyping.value = true;

  try {
    const formData = new FormData();
    formData.append('question', userQuestion);

    const response = await axios.post(route('dashboard.ask'), formData)

    messages.value.push({
      sender: 'bot',
      text: response.data
    });
  } catch (error) {
    messages.value.push({
      sender: 'bot',
      text: 'Sorry, I encountered an error. Please try again.'
    });
    console.error('API Error:', error);
  } finally {
    isTyping.value = false;
  }
};
</script>

<style scoped>
.chat-popup {
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 350px;
  z-index: 1000;
}

.chat-header {
  background-color: #3b82f6;
  color: white;
  padding: 12px 15px;
  border-radius: 8px 8px 0 0;
  cursor: pointer;
  display: flex;
  justify-content: space-between;
}

.chat-body {
  background: white;
  border: 1px solid #e2e8f0;
  border-top: none;
  border-radius: 0 0 8px 8px;
  padding: 15px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  max-height: 400px;
  display: flex;
  flex-direction: column;
}

.chat-messages {
  flex-grow: 1;
  overflow-y: auto;
  margin-bottom: 15px;
  padding-right: 5px;
}

.message {
  margin-bottom: 10px;
  padding: 8px 12px;
  border-radius: 6px;
  max-width: 80%;
}

.user-message {
  background-color: #e5e7eb;
  margin-left: auto;
}

.bot-message {
  background-color: #3b82f6;
  color: white;
  margin-right: auto;
}

.typing-indicator {
  color: #6b7280;
  font-style: italic;
  margin-bottom: 10px;
}

.chat-input {
  display: flex;
  gap: 10px;
}

.chat-input input {
  flex-grow: 1;
  padding: 8px 12px;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
}

.chat-input button {
  background-color: #3b82f6;
  color: white;
  border: none;
  border-radius: 4px;
  padding: 8px 15px;
  cursor: pointer;
}
</style>