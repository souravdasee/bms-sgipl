<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Invoice') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @auth
            @if (request()->user()->role->name === 'Super Admin' || request()->user()->role->name === 'Accountant')
                <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div>
                                <div class="container mx-auto p-4" x-data="formHandler({{ $items->toJson() }})">
                                    <div class="flex justify-center underline mb-10 items-center">
                                        <h2 class="text-2xl font-bold">Add Items</h2>
                                    </div>

                                    @if ($errors->any())
                                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                                            role="alert">
                                            <strong class="font-bold">Whoops!</strong>
                                            <span class="block sm:inline">There were some problems with your input.</span>
                                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form method="POST" action="/showinvoice">
                                        @csrf

                                        <table class="min-w-full bg-white dark:bg-gray-800">
                                            <thead class="bg-gray-800 text-white">
                                                <tr>
                                                    <th class="w-1/12 py-2 px-4">Serial No.</th>
                                                    <th class="w-3/12 py-2 px-4">Description of Goods</th>
                                                    <th class="w-1/12 py-2 px-4">HSN/SAC</th>
                                                    <th class="w-1/12 py-2 px-4">Quantity</th>
                                                    <th class="w-1/12 py-2 px-4">Rate</th>
                                                    <th class="w-1/12 py-2 px-4">Discount</th>
                                                    <th class="w-1/12 py-2 px-4">Amount</th>
                                                    <th class="w-1/12 py-2 px-4">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="(row, index) in rows" :key="index">
                                                    <tr>
                                                        <td class="border px-4 py-2" x-text="index + 1"></td>
                                                        <td class="border px-4 py-2">
                                                            <input type="text"
                                                                :name="'items[' + index + '][description]'"
                                                                x-model="row.description"
                                                                class="w-full dark:bg-gray-700 dark:text-white"
                                                                list="items" @input="updateItem(index)" required>
                                                            <datalist id="items">
                                                                <template x-for="item in items">
                                                                    <option :value="item.name" x-text="item.name">
                                                                    </option>
                                                                </template>
                                                            </datalist>
                                                        </td>
                                                        <td class="border px-4 py-2">
                                                            <input type="text" :name="'items[' + index + '][hsn_sac]'"
                                                                x-model="row.hsn_sac"
                                                                class="w-full dark:bg-gray-700 dark:text-white" required
                                                                readonly>
                                                        </td>
                                                        <td class="border px-4 py-2">
                                                            <input type="number" :name="'items[' + index + '][quantity]'"
                                                                x-model="row.quantity"
                                                                class="w-full dark:bg-gray-700 dark:text-white"
                                                                @input="calculateAmount(index)" required>
                                                        </td>
                                                        <td class="border px-4 py-2">
                                                            <input type="" :name="'items[' + index + '][rate]'"
                                                                x-model="row.rate"
                                                                step="0.00000000000000000000000000000000000000000000000000000000000000000000000000000000001"
                                                                class="w-full dark:bg-gray-700 dark:text-white" required>
                                                        </td>
                                                        <td class="border px-4 py-2">
                                                            <input type="number" :name="'items[' + index + '][discount]'"
                                                                x-model="row.discount"
                                                                class="w-full dark:bg-gray-700 dark:text-white"
                                                                @input="calculateAmount(index)">
                                                        </td>
                                                        <td class="border px-4 py-2">
                                                            <input type="number" :name="'items[' + index + '][amount]'"
                                                                x-model="row.amount"
                                                                class="w-full dark:bg-gray-700 dark:text-white" required
                                                                readonly>
                                                        </td>
                                                        <td class="border px-4 py-2 text-center">
                                                            <button type="button"
                                                                class="bg-red-500 text-white px-2 py-1 rounded"
                                                                @click="removeRow(index)">Remove</button>
                                                        </td>
                                                    </tr>
                                                </template>
                                                <!-- Optional Tax Row -->
                                                <tr>
                                                    <td colspan="8" class="border px-4 py-2">
                                                        <div class="flex justify-between">
                                                            <span>Tax:</span>
                                                            <input type="text" x-model="tax.description" name="tax"
                                                                class="w-1/2 dark:bg-gray-700 dark:text-white"
                                                                list="taxes" @input="updateTax">
                                                            <datalist id="taxes">
                                                                <option value="5%">5%</option>
                                                                <option value="12%">12%</option>
                                                                <option value="18%">18%</option>
                                                                <option value="28%">28%</option>
                                                            </datalist>
                                                            <span>Tax Amount:</span>
                                                            <input type="number" x-model="tax.rate" name="tax_amount"
                                                                class="w-1/4 ml-2 dark:bg-gray-700 dark:text-white"
                                                                readonly>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- Sum Total Row -->
                                                <tr>
                                                    <td colspan="6" class="border px-4 py-2 text-right font-bold">Total:
                                                    </td>
                                                    <td colspan="2" class="border px-4 py-2">
                                                        <input type="number" x-model="total" name="total"
                                                            class="w-full dark:bg-gray-700 dark:text-white" readonly>
                                                    </td>
                                                    {{-- <td class="border px-4 py-2"></td> --}}
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="mt-4">
                                            <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded"
                                                @click="addRow">Add Row</button>
                                            <button type="submit"
                                                class="bg-green-500 text-white px-4 py-2 rounded ml-4">Submit</button>
                                        </div>
                                    </form>
                                </div>

                                <script>
                                    function formHandler(items) {
                                        return {
                                            items: items,
                                            rows: [{
                                                description: '',
                                                hsn_sac: '',
                                                quantity: 1,
                                                rate: null,
                                                discount: 0,
                                                amount: null
                                            }],
                                            tax: {
                                                description: '',
                                                rate: null
                                            },
                                            total: null,
                                            addRow() {
                                                this.rows.push({
                                                    description: '',
                                                    hsn_sac: '',
                                                    quantity: 1,
                                                    rate: null,
                                                    discount: 0,
                                                    amount: null
                                                });
                                            },
                                            removeRow(index) {
                                                this.rows.splice(index, 1);
                                                this.calculateTotal();
                                            },
                                            updateItem(index) {
                                                let selectedItem = this.items.find(item => item.name === this.rows[index].description);
                                                if (selectedItem) {
                                                    this.rows[index].hsn_sac = selectedItem.hsn_sac;
                                                    this.rows[index].rate = selectedItem.rate;
                                                }
                                                this.calculateAmount(index);
                                            },
                                            calculateAmount(index) {
                                                let row = this.rows[index];
                                                let amount = (row.rate * row.quantity) - row.discount;
                                                row.amount = amount > 0 ? amount : 0;
                                                this.calculateTotal();
                                            },
                                            updateTax() {
                                                let taxValue = this.tax.description.replace('%', '');
                                                this.tax.rate = parseFloat(taxValue) || 0;
                                                this.calculateTotal();
                                            },
                                            calculateTotal() {
                                                let subtotal = this.rows.reduce((sum, row) => sum + row.amount, 0);
                                                let taxAmount = subtotal * (this.tax.rate / 100);
                                                this.total = subtotal + taxAmount;
                                            },
                                            taxAmount() {
                                                calculateTotal() - calculateTotal().rows.reduce((sum, row) => sum + row.amount, 0);
                                            }
                                        }
                                    }
                                </script>



                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endauth
    </div>
</x-app-layout>
