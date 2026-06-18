<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

$site = require __DIR__ . '/config/site.php';
$appConfig = [
    'brand' => $site['brand'],
    'ui' => $site['ui'],
    'payments' => $site['payments'],
    'integrations' => $site['integrations'],
    'demoData' => $site['demoData'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($site['meta']['title']) ?></title>
    <meta name="description" content="<?= h($site['meta']['description']) ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .modal-enter {
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-[#fdf2f8] min-h-screen pb-24">
    <div id="loadingScreen" class="fixed inset-0 z-[100] bg-white flex flex-col items-center justify-center transition-opacity duration-500">
        <div id="loadingSpinner" class="w-16 h-16 border-4 border-pink-200 border-t-pink-500 rounded-full animate-spin"></div>
        <p id="loadingText" class="mt-4 text-pink-500 font-bold animate-pulse"><?= h($site['ui']['loadingText']) ?></p>
    </div>

    <header class="bg-white shadow-sm sticky top-0 z-30">
        <div class="max-w-2xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex flex-col">
                <h1 class="text-2xl font-bold text-pink-500 tracking-tight italic"><?= h($site['brand']['name']) ?></h1>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest"><?= h($site['brand']['subtitle']) ?></p>
            </div>
            <button id="cartBtn" class="relative p-2 bg-pink-100 text-pink-600 rounded-full hover:bg-pink-200 transition-all duration-300" type="button">
                <i data-lucide="shopping-cart"></i>
                <span id="cartCount" class="hidden absolute -top-1 -right-1 bg-pink-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white">0</span>
            </button>
        </div>

        <div id="categoryNav" class="max-w-2xl mx-auto flex overflow-x-auto px-4 pb-3 scrollbar-hide space-x-2"></div>
    </header>

    <main id="menuContainer" class="max-w-2xl mx-auto p-4 space-y-8"></main>

    <div id="modalOverlay" class="fixed inset-0 z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm cursor-pointer"></div>
        <div id="configModal" class="modal-enter relative bg-white w-full max-w-lg rounded-t-3xl sm:rounded-3xl shadow-2xl flex flex-col max-h-[90vh]">
            <div class="p-5 border-b flex justify-between items-center bg-white sticky top-0 z-10 rounded-t-3xl">
                <div>
                    <h3 id="modalProdName" class="text-xl font-extrabold text-gray-800"><?= h($site['ui']['modalPlaceholderName']) ?></h3>
                    <p id="modalProdDesc" class="text-xs text-gray-500"><?= h($site['ui']['modalPlaceholderDescription']) ?></p>
                </div>
                <button id="closeModal" class="bg-gray-100 p-2 rounded-full hover:bg-gray-200 transition-colors" type="button">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div id="modalOptions" class="flex-1 overflow-y-auto p-6 space-y-8"></div>

            <div class="p-6 border-t bg-gray-50 rounded-b-3xl">
                <div class="flex justify-between items-end mb-4">
                    <div>
                        <div class="text-xs font-bold text-gray-500 uppercase mb-2"><?= h($site['ui']['quantityLabel']) ?></div>
                        <div class="flex items-center gap-3 bg-gray-200/50 rounded-full p-1">
                            <button id="decreaseQuantity" class="w-8 h-8 rounded-full bg-white shadow-sm flex justify-center items-center font-bold text-gray-600 hover:text-pink-500 transition-colors" type="button">-</button>
                            <span id="modalQuantity" class="font-bold text-gray-800 w-4 text-center">1</span>
                            <button id="increaseQuantity" class="w-8 h-8 rounded-full bg-white shadow-sm flex justify-center items-center font-bold text-gray-600 hover:text-pink-500 transition-colors" type="button">+</button>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-bold text-gray-500 uppercase mb-1"><?= h($site['ui']['subtotalLabel']) ?></div>
                        <div id="modalPrice" class="text-2xl font-black text-pink-600">$0</div>
                    </div>
                </div>
                <button id="addToCartConfirm" class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold py-4 rounded-2xl shadow-lg transition-transform active:scale-[0.98]" type="button">
                    <?= h($site['ui']['addToCartLabel']) ?>
                </button>
            </div>
        </div>
    </div>

    <div id="cartOverlay" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm cursor-pointer" id="cartBackdrop"></div>
        <div id="cartDrawer" class="absolute inset-y-0 right-0 w-full max-w-md bg-white shadow-2xl flex flex-col transition-transform duration-300 translate-x-full">
            <div class="p-6 border-b flex justify-between items-center bg-pink-500 text-white">
                <h2 class="text-xl font-bold flex items-center gap-2"><i data-lucide="shopping-cart"></i> <?= h($site['ui']['cartTitle']) ?></h2>
                <button id="closeCart" type="button"><i data-lucide="x" class="w-7 h-7"></i></button>
            </div>
            <div id="cartItems" class="flex-1 overflow-y-auto p-4 space-y-4"></div>

            <div id="cartFooter" class="p-6 bg-white border-t space-y-4 shadow-up">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1"><?= h($site['ui']['paymentLabel']) ?></label>
                    <div class="grid grid-cols-<?= count($site['payments']) ?> gap-2">
                        <?php foreach ($site['payments'] as $paymentValue => $paymentLabel): ?>
                            <button
                                type="button"
                                data-payment="<?= h($paymentValue) ?>"
                                id="pay-<?= h($paymentValue) ?>"
                                class="pay-btn py-2 text-xs font-bold rounded-xl border-2 border-gray-100 text-gray-400 transition-all"
                            ><?= h($paymentLabel) ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1" for="customerName"><?= h($site['ui']['customerNameLabel']) ?></label>
                    <input id="customerName" type="text" placeholder="<?= h($site['ui']['customerNamePlaceholder']) ?>" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 outline-none focus:border-pink-300 text-sm transition-all duration-300 placeholder:transition-colors">
                </div>

                <div class="flex justify-between items-center text-xl font-black">
                    <span><?= h($site['ui']['totalLabel']) ?></span>
                    <span id="cartTotal" class="text-pink-600">$0</span>
                </div>
                <button id="sendOrder" class="w-full bg-green-500 text-white font-bold py-4 rounded-2xl shadow-lg flex items-center justify-center gap-2 transition-transform active:scale-95 opacity-50 pointer-events-none" type="button">
                    <i data-lucide="message-circle"></i> <?= h($site['ui']['sendOrderLabel']) ?>
                </button>
            </div>
        </div>
    </div>

    <script>
        const APP_CONFIG = <?= js($appConfig) ?>;

        let rawData = {
            modificadores: [],
            seleccionables: [],
            productos: []
        };

        let cart = [];
        let currentSelection = null;
        let selectedPayment = Object.keys(APP_CONFIG.payments)[0] ?? 'Efectivo';

        const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[character]));

        const escapeAttr = (value) => escapeHtml(value);

        const parseBool = (val) => {
            if (val === false || val === 0) return false;
            if (typeof val === 'string') {
                const s = val.toLowerCase().trim();
                if (s === 'false' || s === 'falso' || s === '0' || s === 'no') return false;
            }
            return true;
        };

        function hasRemoteSource() {
            const { menuApiBase, menuSheetId } = APP_CONFIG.integrations;
            return Boolean(menuApiBase && menuSheetId && !menuApiBase.includes('YOUR_DEPLOYMENT_ID'));
        }

        function mapProducts(products) {
            return products.map((product) => ({
                ...product,
                category: product.Categoria || product['Categoría'] || product.category || 'General'
            }));
        }

        function applyData(dataSet) {
            rawData.modificadores = dataSet.modificadores ?? [];
            rawData.seleccionables = dataSet.seleccionables ?? [];
            rawData.productos = mapProducts(dataSet.productos ?? []);

            document.getElementById('loadingScreen').classList.add('opacity-0');
            setTimeout(() => document.getElementById('loadingScreen').classList.add('hidden'), 500);

            renderCategoryNav();

            const categories = [...new Set(rawData.productos.map((product) => product.category))];
            if (categories.length > 0) {
                renderMenu(categories[0]);
            } else {
                document.getElementById('menuContainer').innerHTML = `<p class="text-center text-gray-500 py-10">${escapeHtml(APP_CONFIG.ui.emptyMenuLabel)}</p>`;
            }

            setPaymentMethod(selectedPayment);
            updateCartUI();
        }

        function buildSheetUrl(sheetName) {
            const { menuApiBase, menuApiToken, menuSheetId } = APP_CONFIG.integrations;
            const params = new URLSearchParams({ hoja: sheetName });

            if (menuApiToken) {
                params.set('token', menuApiToken);
            }

            if (menuSheetId) {
                params.set('id', menuSheetId);
            }

            return `${menuApiBase}${menuApiBase.includes('?') ? '&' : '?'}${params.toString()}`;
        }

        async function fetchAllData() {
            if (!hasRemoteSource()) {
                applyData(APP_CONFIG.demoData);
                return;
            }

            try {
                const [modResponse, selResponse, prodResponse] = await Promise.all([
                    fetch(buildSheetUrl('Modificadores')),
                    fetch(buildSheetUrl('Seleccionables')),
                    fetch(buildSheetUrl('TodosLosProductos'))
                ]);

                const [modJson, selJson, prodJson] = await Promise.all([
                    modResponse.json(),
                    selResponse.json(),
                    prodResponse.json()
                ]);

                applyData({
                    modificadores: modJson.data ?? [],
                    seleccionables: selJson.data ?? [],
                    productos: prodJson.data ?? []
                });
            } catch (error) {
                console.error('Error cargando APIs:', error);

                if ((APP_CONFIG.demoData.productos ?? []).length > 0) {
                    applyData(APP_CONFIG.demoData);
                    return;
                }

                const loading = document.getElementById('loadingScreen');
                loading.innerHTML = `
                    <div class="text-center p-6">
                        <p class="text-xl font-bold text-gray-800">${escapeHtml(APP_CONFIG.ui.loadingErrorTitle)}</p>
                        <p class="text-sm text-gray-500 mt-2 mb-4">${escapeHtml(APP_CONFIG.ui.loadingErrorMessage)}</p>
                        <button id="retryLoading" class="bg-pink-500 text-white px-8 py-3 rounded-full font-bold shadow-lg hover:bg-pink-600 transition-colors" type="button">${escapeHtml(APP_CONFIG.ui.retryLabel)}</button>
                    </div>
                `;
                document.getElementById('retryLoading')?.addEventListener('click', () => location.reload());
            }
        }

        function renderCategoryNav() {
            const categories = [...new Set(rawData.productos.map((product) => product.category))];
            const nav = document.getElementById('categoryNav');

            nav.innerHTML = categories.map((category) => `
                <button data-category="${escapeAttr(category)}" type="button" class="cat-btn whitespace-nowrap px-5 py-2 rounded-full text-sm font-bold transition-all bg-white text-pink-400 border border-pink-100">
                    ${escapeHtml(category)}
                </button>
            `).join('');

            nav.querySelectorAll('[data-category]').forEach((button) => {
                button.addEventListener('click', () => renderMenu(button.dataset.category ?? ''));
            });

            const buttons = nav.querySelectorAll('button');
            if (buttons[0]) {
                updateNavStyles(buttons[0]);
            }
        }

        function updateNavStyles(activeButton) {
            document.querySelectorAll('.cat-btn').forEach((button) => {
                button.classList.remove('bg-pink-500', 'text-white', 'shadow-md');
                button.classList.add('bg-white', 'text-pink-400');
            });
            activeButton.classList.add('bg-pink-500', 'text-white', 'shadow-md');
            activeButton.classList.remove('bg-white', 'text-pink-400');
        }

        function renderMenu(category) {
            const container = document.getElementById('menuContainer');
            const products = rawData.productos.filter((product) => product.category === category);

            container.innerHTML = `
                <div class="space-y-4">
                    <h2 class="text-2xl font-black text-gray-800 border-b-2 border-pink-100 pb-2">${escapeHtml(category)}</h2>
                    <div class="grid gap-4">
                        ${products.map((product) => {
                            const isGlobalAvailable = parseBool(product['Disponible-Global']);
                            const sizes = ['C', 'M', 'G', 'XL'].filter((size) => product[size] != null && product[size] !== '' && parseBool(product[`Disponible-${size}`]));
                            const isSoldOut = !isGlobalAvailable || sizes.length === 0;
                            const priceToShow = sizes.length > 0 ? product[sizes[0]] : 0;

                            return `
                                <div ${!isSoldOut ? `data-product="${escapeAttr(product.Nombre)}"` : ''} class="bg-white rounded-2xl p-5 shadow-sm border border-pink-50 flex justify-between items-center group transition-all ${isSoldOut ? 'opacity-60 cursor-not-allowed grayscale' : 'active:scale-95 cursor-pointer'}">
                                    <div class="flex-1 pr-4">
                                        <h3 class="font-extrabold text-gray-800 text-lg group-hover:text-pink-500 transition-colors">${escapeHtml(product.Nombre)}</h3>
                                        <p class="text-xs text-gray-400 mt-1 line-clamp-2">${escapeHtml(product.Descripción || '')}</p>
                                        <div class="mt-3 flex gap-2">
                                            ${isSoldOut
                                                ? `<span class="text-xs font-bold px-2 py-1 bg-red-50 text-red-500 rounded-md uppercase tracking-tighter">${escapeHtml(APP_CONFIG.ui.soldOutLabel)}</span>`
                                                : `<span class="text-xs font-bold px-2 py-1 bg-pink-50 text-pink-500 rounded-md uppercase tracking-tighter">${escapeHtml(APP_CONFIG.ui.fromLabel)} $${escapeHtml(priceToShow)}</span>`
                                            }
                                        </div>
                                    </div>
                                    <div class="${isSoldOut ? 'bg-gray-300' : 'bg-pink-500'} text-white p-3 rounded-xl shadow-lg ${isSoldOut ? '' : 'shadow-pink-100'}">
                                        <i data-lucide="${isSoldOut ? 'ban' : 'plus'}" class="w-6 h-6"></i>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
            `;

            container.querySelectorAll('[data-product]').forEach((productCard) => {
                productCard.addEventListener('click', () => openCustomizer(productCard.dataset.product ?? ''));
            });

            lucide.createIcons();

            document.querySelectorAll('.cat-btn').forEach((button) => {
                if ((button.dataset.category ?? '').trim() === category) {
                    updateNavStyles(button);
                }
            });
        }

        function openCustomizer(productName) {
            const product = rawData.productos.find((item) => item.Nombre === productName);
            if (!product || !parseBool(product['Disponible-Global'])) {
                return;
            }

            const availableSizes = ['C', 'M', 'G', 'XL'].filter((size) => product[size] != null && product[size] !== '' && parseBool(product[`Disponible-${size}`]));
            if (availableSizes.length === 0) {
                return;
            }

            const defaultSize = availableSizes[0];

            document.getElementById('modalQuantity').innerText = '1';

            currentSelection = {
                product,
                size: defaultSize,
                basePrice: Number(product[defaultSize]),
                modifiers: {},
                quantity: 1,
                total: Number(product[defaultSize]),
                obs: ''
            };

            document.getElementById('modalProdName').innerText = product.Nombre;
            document.getElementById('modalProdDesc').innerText = product.Descripción || APP_CONFIG.ui.modalPlaceholderDescription;
            renderModifierSections(product, availableSizes);

            document.getElementById('modalOverlay').classList.remove('hidden');
            calculateModalTotal();
            lucide.createIcons();
        }

        function renderModifierSections(product, availableSizes) {
            const container = document.getElementById('modalOptions');
            let html = `
                <div class="space-y-3">
                    <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest flex items-center gap-2">
                        <i data-lucide="layers" class="w-4 h-4 text-pink-400"></i> ${escapeHtml(APP_CONFIG.ui.sizeLabel)}
                    </h4>
                    <div class="flex gap-2 w-full">
                        ${availableSizes.map((size) => `
                            <button data-size="${escapeAttr(size)}" type="button" id="size-${escapeAttr(size)}" class="flex-1 size-btn py-3 rounded-xl border-2 font-bold transition-all ${currentSelection.size === size ? 'border-pink-500 bg-pink-50 text-pink-600' : 'border-gray-100 text-gray-400'}">
                                ${escapeHtml(size)}<br><span class="text-xs opacity-75">$${escapeHtml(product[size])}</span>
                            </button>
                        `).join('')}
                    </div>
                </div>
            `;

            const modifierIds = (product['Modificadores Aplicables'] || '').split(',').map((value) => value.trim()).filter(Boolean);

            modifierIds.forEach((modifierId) => {
                const modifierDefinition = rawData.modificadores.find((item) => item['ID Modificador'] === modifierId);
                if (!modifierDefinition) {
                    return;
                }

                const options = rawData.seleccionables.filter((item) => item['ID Modificador (Relación)'] === modifierId && parseBool(item.Disponible));
                if (options.length === 0) {
                    return;
                }

                const rules = String(modifierDefinition.Reglas || '');
                const isMultiple = rules.toLowerCase().includes('múltiple');
                const isRequired = rules.toLowerCase().includes('obligatorio');

                html += `
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <h4 class="text-sm font-black text-gray-800">${escapeHtml(modifierDefinition['Nombre a mostrar en UI'])}</h4>
                            <p class="text-xs font-bold text-pink-500 uppercase tracking-tighter">${escapeHtml(rules)}</p>
                        </div>
                        <div class="grid gap-2">
                            ${options.map((option, index) => `
                                <label class="flex items-center justify-between p-4 rounded-2xl border-2 border-gray-50 bg-gray-50/30 cursor-pointer transition-all has-[:checked]:border-pink-500 has-[:checked]:bg-pink-50">
                                    <div class="flex items-center gap-3">
                                        <input
                                            type="${isMultiple ? 'checkbox' : 'radio'}"
                                            name="mod-${escapeAttr(modifierId)}"
                                            value="${escapeAttr(option['ID Opción'])}"
                                            data-modifier-id="${escapeAttr(modifierId)}"
                                            data-option-id="${escapeAttr(option['ID Opción'])}"
                                            data-multiple="${isMultiple ? 'true' : 'false'}"
                                            ${!isMultiple && index === 0 && isRequired ? 'checked' : ''}
                                            class="w-5 h-5 accent-pink-500 rounded-full border-gray-300"
                                        >
                                        <span class="text-sm font-bold text-gray-700">${escapeHtml(option['Etiqueta (UI)'])}</span>
                                    </div>
                                    <span class="text-xs font-black text-pink-500">${Number(option['Precio Extra']) > 0 ? `+$${escapeHtml(option['Precio Extra'])}` : ''}</span>
                                </label>
                            `).join('')}
                        </div>
                    </div>
                `;

                if (!isMultiple && isRequired) {
                    currentSelection.modifiers[modifierId] = [options[0]['ID Opción']];
                }
            });

            html += `
                <div class="space-y-2">
                    <h4 class="text-sm font-black text-gray-800">${escapeHtml(APP_CONFIG.ui.observationsLabel)}</h4>
                    <textarea id="productObs" placeholder="${escapeAttr(APP_CONFIG.ui.observationsPlaceholder)}" class="w-full border-2 border-gray-100 rounded-2xl p-4 text-sm outline-none focus:border-pink-300 h-24 resize-none bg-gray-50/30"></textarea>
                </div>
            `;

            container.innerHTML = html;

            container.querySelectorAll('[data-size]').forEach((button) => {
                button.addEventListener('click', () => updateSize(button.dataset.size ?? ''));
            });

            container.querySelectorAll('[data-option-id]').forEach((input) => {
                input.addEventListener('change', () => toggleModifier(
                    input.dataset.modifierId ?? '',
                    input.dataset.optionId ?? '',
                    input.dataset.multiple === 'true'
                ));
            });
        }

        function updateSize(size) {
            if (!currentSelection) {
                return;
            }

            currentSelection.size = size;
            currentSelection.basePrice = Number(currentSelection.product[size]);

            document.querySelectorAll('.size-btn').forEach((button) => {
                button.classList.remove('border-pink-500', 'bg-pink-50', 'text-pink-600');
                button.classList.add('border-gray-100', 'text-gray-400');
            });

            document.getElementById(`size-${size}`)?.classList.add('border-pink-500', 'bg-pink-50', 'text-pink-600');
            calculateModalTotal();
        }

        function toggleModifier(modifierId, optionId, isMultiple) {
            if (!currentSelection) {
                return;
            }

            if (!currentSelection.modifiers[modifierId]) {
                currentSelection.modifiers[modifierId] = [];
            }

            if (isMultiple) {
                const index = currentSelection.modifiers[modifierId].indexOf(optionId);
                if (index > -1) {
                    currentSelection.modifiers[modifierId].splice(index, 1);
                } else {
                    currentSelection.modifiers[modifierId].push(optionId);
                }
            } else {
                currentSelection.modifiers[modifierId] = [optionId];
            }

            calculateModalTotal();
        }

        function updateQuantity(delta) {
            if (!currentSelection) {
                return;
            }

            if (currentSelection.quantity + delta > 0) {
                currentSelection.quantity += delta;
                document.getElementById('modalQuantity').innerText = String(currentSelection.quantity);
                calculateModalTotal();
            }
        }

        function calculateModalTotal() {
            if (!currentSelection) {
                return;
            }

            let unitTotal = Number(currentSelection.basePrice);
            Object.values(currentSelection.modifiers).flat().forEach((optionId) => {
                const option = rawData.seleccionables.find((item) => item['ID Opción'] === optionId);
                if (option) {
                    unitTotal += Number(option['Precio Extra']);
                }
            });

            currentSelection.total = unitTotal * currentSelection.quantity;
            document.getElementById('modalPrice').innerText = `$${currentSelection.total}`;
        }

        function setPaymentMethod(method) {
            selectedPayment = method;
            document.querySelectorAll('.pay-btn').forEach((button) => {
                button.classList.remove('border-pink-500', 'bg-pink-50', 'text-pink-600');
                button.classList.add('border-gray-100', 'text-gray-400');
            });
            document.querySelectorAll('.pay-btn').forEach((button) => {
                if ((button.dataset.payment ?? '') === method) {
                    button.classList.add('border-pink-500', 'bg-pink-50', 'text-pink-600');
                }
            });
        }

        function addToCart() {
            if (!currentSelection) {
                return;
            }

            const modifiersSummary = [];
            Object.values(currentSelection.modifiers).flat().forEach((optionId) => {
                const option = rawData.seleccionables.find((item) => item['ID Opción'] === optionId);
                if (option) {
                    modifiersSummary.push(option['Etiqueta (UI)']);
                }
            });

            cart.push({
                cartId: Date.now(),
                name: currentSelection.product.Nombre,
                size: currentSelection.size,
                price: currentSelection.total / currentSelection.quantity,
                mods: modifiersSummary,
                obs: document.getElementById('productObs')?.value.trim() ?? '',
                quantity: currentSelection.quantity
            });

            document.getElementById('modalOverlay').classList.add('hidden');
            updateCartUI();

            const cartButton = document.getElementById('cartBtn');
            cartButton.classList.add('scale-110', 'bg-pink-300', 'shadow-md');
            setTimeout(() => cartButton.classList.remove('scale-110', 'bg-pink-300', 'shadow-md'), 300);
        }

        function updateCartUI() {
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            document.getElementById('cartCount').innerText = String(count);
            document.getElementById('cartCount').classList.toggle('hidden', count === 0);
            document.getElementById('cartTotal').innerText = `$${total}`;

            const container = document.getElementById('cartItems');
            if (cart.length === 0) {
                container.innerHTML = `<div class="flex flex-col items-center justify-center py-10 text-gray-400 opacity-60"><i data-lucide="shopping-bag" class="w-12 h-12 mb-3"></i><p>${escapeHtml(APP_CONFIG.ui.emptyCartLabel)}</p></div>`;
            } else {
                container.innerHTML = cart.map((item) => `
                    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 relative">
                        <div class="flex justify-between font-extrabold mb-1">
                            <span class="text-gray-800 text-sm w-3/4 leading-tight">${item.quantity > 1 ? `<span class="text-pink-500">${item.quantity}x</span> ` : ''}${escapeHtml(item.name)} (${escapeHtml(item.size)})</span>
                            <span class="text-pink-500">$${escapeHtml(item.price * item.quantity)}</span>
                        </div>
                        <p class="text-xs text-gray-500">${escapeHtml(item.mods.join(', ') || APP_CONFIG.ui.noExtrasLabel)}</p>
                        ${item.obs ? `<p class="text-xs text-pink-500 mt-1 italic font-medium">${escapeHtml(APP_CONFIG.ui.noteLabel)}: ${escapeHtml(item.obs)}</p>` : ''}
                        <div class="absolute bottom-3 right-4">
                            <button data-remove-id="${escapeAttr(item.cartId)}" class="text-red-300 hover:text-red-500 transition-colors" type="button"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    </div>
                `).join('');
            }

            container.querySelectorAll('[data-remove-id]').forEach((button) => {
                button.addEventListener('click', () => removeFromCart(Number(button.dataset.removeId)));
            });

            const sendButton = document.getElementById('sendOrder');
            if (cart.length === 0) {
                sendButton.classList.add('opacity-50', 'pointer-events-none');
            } else {
                sendButton.classList.remove('opacity-50', 'pointer-events-none');
            }

            lucide.createIcons();
        }

        function removeFromCart(id) {
            cart = cart.filter((item) => item.cartId !== id);
            updateCartUI();
        }

        function sendToWhatsApp() {
            if (cart.length === 0) {
                return;
            }

            const nameInput = document.getElementById('customerName');
            const name = nameInput.value.trim();
            if (!name) {
                nameInput.classList.add('border-red-500', 'bg-red-50', 'placeholder-red-400');
                nameInput.placeholder = APP_CONFIG.ui.customerNameRequiredPlaceholder;
                nameInput.animate([
                    { transform: 'translateX(0px)' },
                    { transform: 'translateX(-5px)' },
                    { transform: 'translateX(5px)' },
                    { transform: 'translateX(-5px)' },
                    { transform: 'translateX(5px)' },
                    { transform: 'translateX(0px)' }
                ], { duration: 400, iterations: 1 });

                const clearError = () => {
                    nameInput.classList.remove('border-red-500', 'bg-red-50', 'placeholder-red-400');
                    nameInput.placeholder = APP_CONFIG.ui.customerNamePlaceholder;
                    nameInput.removeEventListener('input', clearError);
                };

                nameInput.addEventListener('input', clearError);
                nameInput.focus();
                return;
            }

            const whatsappNumber = String(APP_CONFIG.integrations.whatsappNumber || '').replace(/\D/g, '');
            if (!whatsappNumber || whatsappNumber === '5210000000000') {
                nameInput.classList.add('border-amber-400', 'bg-amber-50');
                setTimeout(() => nameInput.classList.remove('border-amber-400', 'bg-amber-50'), 1200);
                console.warn(APP_CONFIG.ui.whyDisabledMessage);
                return;
            }

            let message = `*Pedido de ${APP_CONFIG.brand.name}*\n`;
            message += `Cliente: ${name}\n`;
            message += `Pago: *${selectedPayment}*\n`;
            message += '--------------------------\n';

            cart.forEach((item) => {
                message += `- ${item.quantity}x ${item.name} [${item.size}] ($${item.price * item.quantity})\n`;
                if (item.mods.length > 0) {
                    message += `  Extras: ${item.mods.join(', ')}\n`;
                }
                if (item.obs) {
                    message += `  _${APP_CONFIG.ui.noteLabel}: ${item.obs}_\n`;
                }
            });

            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            message += '--------------------------\n';
            message += `*${APP_CONFIG.ui.totalLabel}: $${total}*`;

            window.open(`https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`, '_blank', 'noopener');
        }

        document.getElementById('closeModal').addEventListener('click', () => document.getElementById('modalOverlay').classList.add('hidden'));
        document.getElementById('addToCartConfirm').addEventListener('click', addToCart);
        document.getElementById('decreaseQuantity').addEventListener('click', () => updateQuantity(-1));
        document.getElementById('increaseQuantity').addEventListener('click', () => updateQuantity(1));
        document.getElementById('cartBtn').addEventListener('click', () => {
            document.getElementById('cartOverlay').classList.remove('hidden');
            setTimeout(() => document.getElementById('cartDrawer').classList.remove('translate-x-full'), 10);
        });
        document.getElementById('closeCart').addEventListener('click', () => {
            document.getElementById('cartDrawer').classList.add('translate-x-full');
            setTimeout(() => document.getElementById('cartOverlay').classList.add('hidden'), 300);
        });
        document.getElementById('sendOrder').addEventListener('click', sendToWhatsApp);
        document.querySelectorAll('[data-payment]').forEach((button) => {
            button.addEventListener('click', () => setPaymentMethod(button.dataset.payment ?? selectedPayment));
        });
        document.querySelector('#modalOverlay > div.absolute').addEventListener('click', () => {
            document.getElementById('modalOverlay').classList.add('hidden');
        });
        document.getElementById('cartBackdrop').addEventListener('click', () => {
            document.getElementById('cartDrawer').classList.add('translate-x-full');
            setTimeout(() => document.getElementById('cartOverlay').classList.add('hidden'), 300);
        });

        document.addEventListener('DOMContentLoaded', fetchAllData);
    </script>
</body>
</html>
