const decimalPoint = ',';
const groupingSeparator = '.';

/*
    Available property
    - className
    - tHClassName
    - tDClassName
*/
const generateTable = (data, columns, property = {}) => {
    const table = document.createElement('table');
    if (property.hasOwnProperty('className')) table.className = property.className;

    const thead = document.createElement('thead');
    const tbody = document.createElement('tbody');

    const headerRow = document.createElement('tr');
    columns.forEach(column => {
    const th = document.createElement('th');
    if (property.hasOwnProperty('tHClassName')) th.className = property.tHClassName;
        th.textContent = column.hasOwnProperty('name') ? column.name : column.data
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    if (data.length == 0) {
        const row = document.createElement('tr');
        const td = document.createElement('td');
        if (property.hasOwnProperty('tDClassName')) td.classList.add(...property.tDClassName.split(' '));
        
        td.innerHTML = "No Data";
        td.colSpan = columns.length;
        row.appendChild(td);
        tbody.appendChild(row);
    } else {
        data.forEach(item => {
            const row = document.createElement('tr');
            columns.forEach(column => {
                const td = document.createElement('td');
                if (property.hasOwnProperty('tDClassName')) td.classList.add(...property.tDClassName.split(' '));
                if (column.hasOwnProperty('className')) td.classList.add(...column.className.split(' '));
                
                if (column.hasOwnProperty('render')) td.innerHTML = column.render(item[column.data], item)
                else td.innerHTML = item[column.data];
                
                row.appendChild(td);
            });
            tbody.appendChild(row);
        });
    }

    table.appendChild(tbody);

    return table;
}

function generatePagination(currentPage = 1, maxPages = 1) {
    const visibleCount = 3;

    const pagination = document.createElement('div');
    pagination.className = 'flex items-center';

    const prevArrow = document.createElement('button');
    prevArrow.textContent = '«';
    prevArrow.setAttribute('data-page', 1);
    prevArrow.className = 'px-3 py-1 border border-1 hover:bg-gray-100 transition change-page';

    const nextArrow = document.createElement('button');
    nextArrow.textContent = '»';
    prevArrow.setAttribute('data-page', maxPages);
    nextArrow.className = 'px-3 py-1 border border-1 hover:bg-gray-100 transition change-page';

    const pageNumbers = document.createElement('div');
    pageNumbers.className = 'page-numbers';

    function updatePagination() {
        pageNumbers.innerHTML = '';
        let isVisble = true;

        const visiblePage = [];
        const startingPoint = currentPage - Math.floor(visibleCount / 2);
        const endPoint = currentPage + Math.floor(visibleCount / 2);

        if (currentPage == 1) visiblePage.push(endPoint + 1);
        for (let i = startingPoint; i <= endPoint; i++) {
            if (i >= 1 && i <= maxPages) visiblePage.push(i);
        }
        if (currentPage == maxPages) visiblePage.push(startingPoint - 1);

        for (let i = 1; i <= maxPages; i++) {
            if (visiblePage.includes(i)) {
                isVisble = true;
                addPageNumber(i);
            } else {
                if (isVisble == true) addSeparatorNumber(i);
                isVisble = false;
            }
        }
    }

    function addSeparatorNumber(page) {
        const ellipsis = document.createElement('button');
        ellipsis.className = 'px-3 py-1 border border-1 hover:bg-gray-100 transition change-page';
        ellipsis.textContent = '...';
        ellipsis.setAttribute('data-page', page);
        pageNumbers.appendChild(ellipsis);
    }

    function addPageNumber(page) {
        const pageNumber = document.createElement('button');
        pageNumber.className = 'px-3 py-1 border border-1 transition change-page';
        if (page == currentPage) {
            pageNumber.classList.add('bg-gray-100');
        } else {
            pageNumber.classList.add('hover:bg-gray-100');
        }
        pageNumber.textContent = page;
        pageNumber.setAttribute('data-page', page);
        pageNumbers.appendChild(pageNumber);
    }

    updatePagination();

    pagination.appendChild(prevArrow);
    pagination.appendChild(pageNumbers);
    pagination.appendChild(nextArrow);

    return pagination;
}

function addQueryParam(urlString, key, value) {
    let url = new URL(urlString);
    let params = new URLSearchParams(url.search);
    params.set(key, value);
    url.search = params.toString();

    return url.toString();
}

const currencyFormat = (value, decimal = true) => {
    let isNegative = false;

    if (value < 0) {
        isNegative = true;
        value = Math.abs(value);
    }

    let val = String(value);
    val = val.replace(/^0+/, '');

    if (!val) return "0";

    if (decimal) {
      val = val.split(groupingSeparator)
      val[0] = val[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, decimalPoint)
      if (val[1] !== undefined) val[1] = val[1].slice(0, 2)

      val = val.join(groupingSeparator)
    } else {
      val = val.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, decimalPoint)
    }

    if (isNegative) val = "-" + val;
    return val;
  }

const sanitizeNumber = (value) => {
    if (typeof value === undefined) return 0
    const replaceSeparator = `\\${decimalPoint}`
    const re = new RegExp(replaceSeparator, 'g')
    
    return String(value).replace(re, '')
  }

 const currency2Float = (value) => {
    if (typeof value === undefined) return 0
    value = sanitizeNumber(value)

    const replaceSeparator = `\\${decimalPoint}`
    const re = new RegExp(replaceSeparator, 'g')
    
    const num = String(value).replace(re, ',')
    return parseFloat(num)
  }