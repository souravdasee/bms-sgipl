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
                                                            <input type="number" :name="'items[' + index + '][rate]'"
                                                                x-model="row.rate" step="0.01"
                                                                class="w-full dark:bg-gray-700 dark:text-white" required
                                                                readonly>
                                                        </td>
                                                        <td class="border px-4 py-2">
                                                            <select :name="'items[' + index + '][discount_type]'"
                                                                x-model="row.discount_type" @change="calculateAmount(index)"
                                                                class="w-full dark:bg-gray-700 dark:text-white">
                                                                <option value="percentage">Percentage</option>
                                                                <option value="amount">INR</option>
                                                            </select>
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
                                                                @click="confirmRemoveRow(index)">Remove</button>
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
                                                            <input type="number" x-model="tax.amount" name="tax_amount"
                                                                class="w-1/4 ml-2 dark:bg-gray-700 dark:text-white"
                                                                readonly>
                                                        </div>
                                                        <div class="flex justify-between mt-2" x-show="isSameState">
                                                            <span>CGST:</span>
                                                            <input type="number" x-model="tax.cgst" name="cgst"
                                                                class="w-1/4 ml-2 dark:bg-gray-700 dark:text-white"
                                                                readonly>
                                                            <span>SGST:</span>
                                                            <input type="number" x-model="tax.sgst" name="sgst"
                                                                class="w-1/4 ml-2 dark:bg-gray-700 dark:text-white"
                                                                readonly>
                                                        </div>
                                                        <div class="flex justify-between mt-2" x-show="!isSameState">
                                                            <span>IGST:</span>
                                                            <input type="number" x-model="tax.igst" name="igst"
                                                                class="w-1/4 ml-2 dark:bg-gray-700 dark:text-white"
                                                                readonly>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- Optional Expense Row -->
                                                <template x-for="(expense, eindex) in expenses" :key="eindex">
                                                    <tr>
                                                        <td colspan="8" class="border px-4 py-2">
                                                            <div class="flex justify-between">
                                                                <input type="text" x-model="expense.description"
                                                                    :name="'expenses[' + eindex + '][description]'"
                                                                    class="w-1/2 dark:bg-gray-700 dark:text-white"
                                                                    list="expense_types" required>
                                                                <datalist id="expense_types">
                                                                    <option value="Travel Expense">Travel Expense</option>
                                                                    <option value="Service Expense">Service Expense
                                                                    </option>
                                                                    <option value="Installation Fee">Installation Fee
                                                                    </option>
                                                                    <option value="Convenience Fee">Convenience Fee
                                                                    </option>
                                                                </datalist>
                                                                <input type="number" x-model="expense.amount"
                                                                    :name="'expenses[' + eindex + '][amount]'"
                                                                    class="w-1/4 ml-2 dark:bg-gray-700 dark:text-white"
                                                                    @input="calculateTotal" required>
                                                                <button type="button"
                                                                    class="bg-red-500 text-white px-2 py-1 rounded"
                                                                    @click="removeExpenseRow(eindex)">Remove</button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                                <tr>
                                                    <td colspan="8" class="border px-4 py-2">
                                                        <button type="button"
                                                            class="bg-blue-500 text-white px-4 py-2 rounded"
                                                            @click="addExpenseRow">Add Expense</button>
                                                    </td>
                                                </tr>
                                                <!-- Sum Total Row -->
                                                <tr>
                                                    <td colspan="7" class="border px-4 py-2 text-right font-bold">
                                                        Total:</td>
                                                    <td class="border px-4 py-2">
                                                        <input type="number" x-model="total" name="total"
                                                            class="w-full dark:bg-gray-700 dark:text-white" readonly>
                                                    </td>
                                                </tr>
                                                <!-- Rounded off Amount -->
                                                <tr>
                                                    <td colspan="6" class="border-l border-b px-4 py-2 text-right">
                                                        Rounded off Amount:</td>
                                                    <td colspan="2" class="border-b border-r px-4 py-2">
                                                        <input type="number"
                                                            x-model="(Math.round(total) - total).toFixed(3)"
                                                            name="round_off_total"
                                                            class="w-full dark:bg-gray-700 dark:text-white" readonly>
                                                    </td>
                                                </tr>
                                                <!-- Amount to Pay -->
                                                <tr>
                                                    <td colspan="6" class="border-l border-b px-4 py-2 text-right">
                                                        Amount to Pay:</td>
                                                    <td colspan="2" class="border-b border-r px-4 py-2">
                                                        <input type="number" x-model="Math.round(total)"
                                                            name="total_amount_pay"
                                                            class="w-full dark:bg-gray-700 dark:text-white" readonly>
                                                    </td>
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

                                    <!-- Modal for confirmation -->
                                    <div x-show="showModal" class="fixed z-10 inset-0 overflow-y-auto"
                                        aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <div
                                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                                aria-hidden="true"></div>
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                aria-hidden="true">&#8203;</span>
                                            <div
                                                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <div class="sm:flex sm:items-start">
                                                        <div
                                                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                            <svg class="h-6 w-6 text-red-600" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor"
                                                                aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </div>
                                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                            <h3 class="text-lg leading-6 font-medium text-gray-900"
                                                                id="modal-title">Remove Item</h3>
                                                            <div class="mt-2">
                                                                <p class="text-sm text-gray-500">Are you sure you want to
                                                                    remove this item? This action cannot be undone.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                    <button type="button"
                                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                                        @click="confirmDelete">Remove</button>
                                                    <button type="button"
                                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                                        @click="cancelDelete">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                                discount_type: 'percentage',
                                                discount: 0,
                                                amount: null
                                            }],
                                            expenses: [],
                                            tax: {
                                                description: '',
                                                rate: null,
                                                amount: null,
                                                cgst: null,
                                                sgst: null,
                                                igst: null
                                            },
                                            total: null,
                                            showModal: false,
                                            deleteIndex: null,
                                            isSameState: false,
                                            addRow() {
                                                this.rows.push({
                                                    description: '',
                                                    hsn_sac: '',
                                                    quantity: 1,
                                                    rate: null,
                                                    discount_type: 'percentage',
                                                    discount: 0,
                                                    amount: null
                                                });
                                            },
                                            confirmRemoveRow(index) {
                                                this.deleteIndex = index;
                                                this.showModal = true;
                                            },
                                            removeExpenseRow(eindex) {
                                                this.expenses.splice(eindex, 1);
                                                this.calculateTotal();
                                            },
                                            confirmDelete() {
                                                if (this.deleteIndex !== null) {
                                                    this.rows.splice(this.deleteIndex, 1);
                                                    this.deleteIndex = null;
                                                    this.calculateTotal();
                                                }
                                                this.showModal = false;
                                            },
                                            cancelDelete() {
                                                this.deleteIndex = null;
                                                this.showModal = false;
                                            },
                                            addExpenseRow() {
                                                this.expenses.push({
                                                    description: '',
                                                    amount: null
                                                });
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
                                                let discount = row.discount_type === 'percentage' ? (row.rate * row.quantity) * (row.discount / 100) :
                                                    row.discount;
                                                let amount = (row.rate * row.quantity) - discount;
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
                                                this.tax.amount = taxAmount;
                                                if (this.isSameState) {
                                                    this.tax.cgst = taxAmount / 2;
                                                    this.tax.sgst = taxAmount / 2;
                                                    this.tax.igst = null;
                                                } else {
                                                    this.tax.cgst = null;
                                                    this.tax.sgst = null;
                                                    this.tax.igst = taxAmount;
                                                }
                                                let expenseTotal = this.expenses.reduce((sum, expense) => sum + expense.amount, 0);
                                                this.total = subtotal + taxAmount + expenseTotal;
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
