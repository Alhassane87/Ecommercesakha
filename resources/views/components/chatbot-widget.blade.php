<div x-data="chatbotWidget()">
    <div x-show="isOpen"
         @click.away="handleClickAway"
         class="fixed bottom-20 right-4 sm:right-6 w-[calc(100vw-2rem)] sm:w-96 h-[32rem] bg-white rounded-lg shadow-2xl z-50 flex flex-col"
         x-transition>
        <div class="text-white p-4 rounded-t-lg flex justify-between items-center" style="background: var(--button-primary)">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-blue-600 font-bold">
                    S
                </div>
                <div>
                    <h3 class="font-semibold">Assistant Sakha</h3>
                    <p class="text-xs opacity-90">En ligne</p>
                </div>
            </div>
            <button @click="isOpen = false" class="text-white hover:bg-blue-800 rounded p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50" x-ref="messageContainer">
            <template x-if="messages.length === 0">
                <div class="text-center text-gray-500 mt-8">
                    <p class="text-sm">Bonjour, comment puis-je vous aider aujourd hui ?</p>
                </div>
            </template>

            <template x-for="(message, index) in messages" :key="message.id || index">
                <div :class="message.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="message.role === 'user'
                        ? 'bg-blue-600 text-white rounded-lg rounded-br-none px-4 py-2 max-w-[78%]'
                        : 'bg-white text-gray-800 rounded-lg rounded-bl-none px-4 py-2 max-w-[88%] shadow'">
                        <p class="text-sm whitespace-pre-line" x-text="message.content"></p>

                        <template x-if="message.role === 'assistant' && message.products && message.products.length">
                            <div class="mt-3 space-y-2">
                                <template x-for="product in message.products" :key="(message.id || index) + '-' + product.id">
                                    <a :href="product.url"
                                       class="block rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition p-2">
                                        <div class="flex items-center gap-3">
                                            <template x-if="product.image_url">
                                                <img :src="product.image_url"
                                                     :alt="product.name"
                                                     class="w-14 h-14 rounded-md object-cover bg-gray-100 border border-gray-200">
                                            </template>
                                            <template x-if="!product.image_url">
                                                <div class="w-14 h-14 rounded-md bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            </template>

                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 truncate" x-text="product.name"></p>
                                                <p class="text-xs text-gray-500 truncate" x-text="product.category || 'Produit'"></p>
                                                <p class="text-xs font-bold text-emerald-600 mt-0.5" x-text="product.price"></p>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </template>

                        <span class="text-xs opacity-70 mt-2 block" x-text="formatTime(message.time)"></span>
                    </div>
                </div>
            </template>

            <template x-if="isLoading">
                <div class="flex justify-start">
                    <div class="bg-white rounded-lg px-4 py-3 shadow">
                        <div class="flex gap-1">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="p-4 border-t bg-white rounded-b-lg">
            <form @submit.prevent="sendMessage" class="flex gap-2">
                <input type="text"
                       x-model="currentMessage"
                       placeholder="Tapez votre message..."
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                       :disabled="isLoading">
                <button type="submit"
                        class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="isLoading || !currentMessage.trim()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <button @click="toggleChat"
            class="fixed bottom-6 right-6 bg-blue-600 text-white w-14 h-14 rounded-full shadow-lg hover:bg-blue-700 transition flex items-center justify-center z-40">
        <svg x-show="!isOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <svg x-show="isOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
</div>

<script>
function chatbotWidget() {
    return {
        isOpen: false,
        messages: [],
        currentMessage: '',
        isLoading: false,
        conversationId: null,

        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen && this.messages.length === 0) {
                this.loadHistory();
            }
        },

        handleClickAway() {
        },

        async sendMessage() {
            if (!this.currentMessage.trim() || this.isLoading) {
                return;
            }

            const userMessage = this.currentMessage.trim();
            this.messages.push({
                id: `u-${Date.now()}`,
                role: 'user',
                content: userMessage,
                products: [],
                time: new Date(),
            });

            this.currentMessage = '';
            this.isLoading = true;
            this.scrollToBottom();

            try {
                const response = await fetch('/chatbot/message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ message: userMessage }),
                });

                const data = await response.json();

                if (data.success) {
                    this.conversationId = data.conversation_id;
                    this.messages.push({
                        id: `a-${Date.now()}`,
                        role: 'assistant',
                        content: data.response || 'Je peux vous aider dans votre recherche.',
                        products: Array.isArray(data.products) ? data.products : [],
                        time: new Date(),
                    });
                } else {
                    this.messages.push({
                        id: `e-${Date.now()}`,
                        role: 'assistant',
                        content: 'Desole, une erreur s est produite. Veuillez reessayer.',
                        products: [],
                        time: new Date(),
                    });
                }
            } catch (error) {
                console.error('Chatbot error:', error);
                this.messages.push({
                    id: `n-${Date.now()}`,
                    role: 'assistant',
                    content: 'Erreur reseau. Verifiez votre connexion internet.',
                    products: [],
                    time: new Date(),
                });
            } finally {
                this.isLoading = false;
                this.scrollToBottom();
            }
        },

        async loadHistory() {
            if (!this.conversationId) {
                return;
            }

            try {
                const response = await fetch(`/chatbot/history?conversation_id=${this.conversationId}`);
                const data = await response.json();

                if (data.success && Array.isArray(data.messages)) {
                    this.messages = data.messages.map((msg, index) => {
                        const products = msg.metadata && Array.isArray(msg.metadata.products)
                            ? msg.metadata.products
                            : [];

                        return {
                            id: msg.id || `h-${index}`,
                            role: msg.role,
                            content: msg.content,
                            products: products,
                            time: new Date(msg.created_at),
                        };
                    });
                    this.scrollToBottom();
                }
            } catch (error) {
                console.error('Error loading history:', error);
            }
        },

        scrollToBottom() {
            this.$nextTick(() => {
                if (this.$refs.messageContainer) {
                    this.$refs.messageContainer.scrollTop = this.$refs.messageContainer.scrollHeight;
                }
            });
        },

        formatTime(date) {
            return new Date(date).toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit',
            });
        },
    };
}
</script>

