<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('detectDisease') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="uploadFile1"
                            class="bg-white text-gray-500 font-semibold text-base rounded max-w-sm h-40 flex flex-col items-center justify-center cursor-pointer border-2 border-gray-300 border-dashed mx-auto font-[sans-serif]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-2 fill-gray-500" viewBox="0 0 32 32">
                                <path
                                    d="M23.75 11.044a7.99 7.99 0 0 0-15.5-.009A8 8 0 0 0 9 27h3a1 1 0 0 0 0-2H9a6 6 0 0 1-.035-12 1.038 1.038 0 0 0 1.1-.854 5.991 5.991 0 0 1 11.862 0A1.08 1.08 0 0 0 23 13a6 6 0 0 1 0 12h-3a1 1 0 0 0 0 2h3a8 8 0 0 0 .75-15.956z"
                                    data-original="#000000" />
                                <path
                                    d="M20.293 19.707a1 1 0 0 0 1.414-1.414l-5-5a1 1 0 0 0-1.414 0l-5 5a1 1 0 0 0 1.414 1.414L15 16.414V29a1 1 0 0 0 2 0V16.414z"
                                    data-original="#000000" />
                            </svg>
                            <span id="file-name" class="text-gray-500">Upload file</span>

                            <input type="file" id='uploadFile1' name="tomato_image" class="hidden" onchange="updateFileName()" />
                            <p class="text-xs font-medium text-gray-400 mt-2">PNG, JPG, SVG, WEBP, and GIF are allowed.</p>
                        </label>
                        <div class="mt-4 flex justify-center">
                            <label for="language" class="mr-2">Select Language:</label>
                            <select name="language" id="language" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-300 rounded">
                                <option value="english">English</option>
                                <option value="kiswahili">Kiswahili</option>
                            </select>
                        </div>
                        <div class="mt-4 flex justify-center">
                            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Submit</button>
                        </div>
                    </form>
                    @if (isset($prediction))
                        <div id="predictionModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                                <h3 class="text-lg font-semibold mb-4">Disease Prediction Results:</h3>
                                <table class="min-w-full bg-white dark:bg-gray-800">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Model</th>
                                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Disease Name</th>
                                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Accuracy (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">01_cnn_model</td>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $prediction['model1']['predicted_class'] }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $prediction['model1']['accuracy'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">02_CNN_VGG</td>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $prediction['model2']['predicted_class'] }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $prediction['model2']['accuracy'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">03_CNN_Inception</td>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $prediction['model3']['predicted_class'] }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $prediction['model3']['accuracy'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">04_CNN_EfficientNet</td>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $prediction['model4']['predicted_class'] }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $prediction['model4']['accuracy'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Final Prediction</td>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $prediction['final_prediction']['predicted_disease'] }}</td>
                                            <!-- <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $prediction['final_prediction']['accuracy'] }}</td> -->
                                        </tr>                                        
                                    </tbody>
                                </table>
                                <div class="mt-4 flex justify-center">
                                    <button onclick="showDetails()" class="bg-blue-500 text-white py-2 px-4 rounded">Next</button>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($diseaseDetails))
                        <div id="detailsModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                                <h3 class="text-lg font-semibold mb-4">{{ $diseaseDetails['name'] }}</h3>
                                <p class="mb-2"><strong>Description:</strong> {{ $diseaseDetails['description'] }}</p>
                                <p class="mb-2"><strong>Remedy:</strong> {{ $diseaseDetails['recommendations']['remedy'] }}</p>
                                <p class="mb-2"><strong>Other Recommendations:</strong> {{ $diseaseDetails['recommendations']['other'] }}</p>
                                <div class="mt-4 flex justify-center">
                                    <button onclick="closeDetailsModal()" class="bg-blue-500 text-white py-2 px-4 rounded">Close</button>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- @if (isset($diseaseDetails))
                        <div id="chatbotModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                                <h3 class="text-lg font-semibold mb-4">Chat with our Expert</h3>
                                <div id="chatbox" class="border border-gray-300 p-4 mb-4 h-64 overflow-y-scroll">
                                  
                                </div>
                                <div class="flex">
                                    <input type="text" id="userMessage" class="flex-1 border border-gray-300 p-2 rounded-l-lg" placeholder="Type your message...">
                                    <button onclick="sendMessage()" class="bg-blue-500 text-white py-2 px-4 rounded-r-lg">Send</button>
                                </div>
                                <div class="mt-4 flex justify-center">
                                    <button onclick="closeChatbotModal()" class="bg-blue-500 text-white py-2 px-4 rounded">Close</button>
                                </div>
                            </div>
                        </div>
                    @endif -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateFileName() {
            const input = document.getElementById('uploadFile1');
            const fileName = input.files[0] ? input.files[0].name : 'Upload file';
            document.getElementById('file-name').textContent = fileName;
        }

        function showDetails() {
            console.log('Showing details modal');
            document.getElementById('predictionModal').style.display = 'none';
            document.getElementById('detailsModal').style.display = 'flex';
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').style.display = 'none';
            document.getElementById('chatbotModal').style.display = 'flex';
        }

        function closeChatbotModal() {
            document.getElementById('chatbotModal').style.display = 'none';
        }

        function sendMessage() {
            const userMessage = document.getElementById('userMessage').value;
            if (userMessage.trim() === '') return;

            // Append user message to chatbox
            const chatbox = document.getElementById('chatbox');
            const userMessageElement = document.createElement('div');
            userMessageElement.className = 'text-right mb-2';
            userMessageElement.textContent = userMessage;
            chatbox.appendChild(userMessageElement);

            // Clear input
            document.getElementById('userMessage').value = '';

            // Send message to server
            fetch('{{ route('chatbot') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: userMessage, disease: '{{ $diseaseDetails['name'] ?? '' }}' })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Append bot response to chatbox
                const botMessageElement = document.createElement('div');
                botMessageElement.className = 'text-left mb-2';
                botMessageElement.textContent = data.response;
                chatbox.appendChild(botMessageElement);

                // Scroll to bottom
                chatbox.scrollTop = chatbox.scrollHeight;
            })
            .catch(error => {
                console.error('Error:', error);
                const errorMessageElement = document.createElement('div');
                errorMessageElement.className = 'text-left mb-2 text-red-500';
                errorMessageElement.textContent = 'Error: ' + error.message;
                chatbox.appendChild(errorMessageElement);
            });
        }
        @if (isset($prediction))
            console.log('Prediction modal should be displayed');
            document.getElementById('predictionModal').style.display = 'flex';
        @endif
    </script>
</x-app-layout>