(function($) {
  var active_item;
  var item_width = 125;
  var item_height = 116 + 6;
  var recycled_items = [];
  var iconsFiltered = [];

  jQuery(document).on('click', 'li[data-icon]', function() {

    var val = jQuery(this).attr('data-icon');

    active_item.find('.icon-value').val(val);
    active_item.find('.acf-icon-picker__icon').html(
      '<i class="' + val + '"></i>'
    );
    jQuery('.acf-icon-picker__popup-holder').trigger('close');
    jQuery('.acf-icon-picker__popup-holder').remove();

    active_item
      .parents('.acf-icon-picker')
      .find('.acf-icon-picker__remove')
      .addClass('acf-icon-picker__remove--active');
  });

  function initialize_field($el) {
    $el.find('.acf-icon-picker__icon').on('click', function(e) {
      e.preventDefault();
      var is_open = true;
      active_item = $(this).parent();

      iconsFiltered = active_item.find('.icons-list');
      iconsFiltered = iconsFiltered && iconsFiltered.val() ? JSON.parse(iconsFiltered.val()) : [];

      if (iv.icons.length == 0) {
        var list = '<p>' + iv.no_icons_msg + '</p>';
      } else {
        var list = `<ul id="icons-list">`;
        list += `</ul>`;
      }

      jQuery('body').append(
        `<div class="acf-icon-picker__popup-holder">
        <div class="acf-icon-picker__popup">
        <a class="acf-icon-picker__popup__close" href="javascript:">Fermer</a>
        <h4 class="acf-icon-picker__popup__title">Choisir une icône</h4>
        <input class="acf-icon-picker__filter" type="text" id="filterIcons" placeholder="Filtrer par icône" />
          ${list}
        </div>
      </div>`
      );

      jQuery('.acf-icon-picker__popup-holder').on('close', function() {
        is_open = false;
      });

      var $list = $('#icons-list');
      var margin = 200; // number of px to show above and below.
      var columns = 4;

      var icons = iconsFiltered && iconsFiltered.length ? iconsFiltered : iv.icons;
      var arrayIcons = icons;

      function setListHeight() {
        var nb_items = icons.length;
        var total_lines = Math.ceil(nb_items / columns);
        $list.height(total_lines * item_height);
      }

      function removeAllItems() {
        $('[data-acf-icon-index]').each(function(i, el) {
          var $el = $(el);
          recycled_items.push($el);
          $el.remove();
        });
      }

      function render() {

        if (!is_open) return;

        var scroll_top = $('.acf-icon-picker__popup').scrollTop();
        var scroll_min = scroll_top - item_height - margin;
        var scroll_max = scroll_top + $('.acf-icon-picker__popup').height() + margin;

        // Get the index of the first and last element from array we will show.
        var index_min = Math.ceil(scroll_min / item_height) * columns;
        var index_max = Math.ceil(scroll_max / item_height) * columns;

        // remove unneeded items and add them to recycled items.
        $('[data-acf-icon-index]').each(function(i, el) {
          var $el = $(el);
          var index = $el.attr('data-acf-icon-index');
          var name = $el.attr('data-icon');
          // Check if we have the element in the resulting array.
          var elementExist = function() {
            return icons.find(function (icon) {
              return icon === name;
            });
          };

          if ((index < index_min || index > index_max || !elementExist())) {
            recycled_items.push($el);
            $el.remove();
          }
        });

        for (var i = index_min; i < index_max; i++) {
          if (i < 0 || i >= icons.length) continue;
          var icon = icons[i];

          // Calculate the position of the item.
          var y = Math.floor(i / columns) * item_height;
          var x = i % columns * item_width;

          // If we already have the element visible we can continue
          var $el = $(`[data-acf-icon-index="${i}"][data-icon="${icon}"]`);

          // If item already exist we can skip.
          if ($el.length) continue;

          if (recycled_items.length) {
            // If there are recycled items reuse one.
            $el = recycled_items.pop();
          } else {
            // Or create a new element.
            $el = $(`<li>
            <div class="acf-icon-picker__popup-icon">
              <i></i>
            </div>
            <span class="icons-list__name"></span>
          </li>`);
          }

          // We use attr instead of data since we want to use css selector.
          $el.attr({
            'data-icon': icon,
            'data-acf-icon-index': i
          }).css({
            transform: `translate(${x}px, ${y}px)`
          });
          $el.find('.icons-list__name').text(icon.replace('icon-', ''));
          $el.find('i').removeClass();
          $el.find('i').addClass(icon);
          $list.append($el);

        }

        requestAnimationFrame(render);
      }
      if (icons.length) {
        setListHeight();
        render();
      }

      const iconsFilter = document.querySelector('#filterIcons');

      function filterIcons(wordToMatch) {
        return arrayIcons.filter(icon => {
          var name = icon.replace(/[-_]/g, ' ');
          const regex = new RegExp(wordToMatch, 'gi');
          return name.match(regex);
        });
      }

      function displayResults() {
        icons = filterIcons($(this).val());
        removeAllItems();
        setListHeight();
      }

      iconsFilter.focus();

      iconsFilter.addEventListener('keyup', displayResults);

      // Closing
      jQuery('.acf-icon-picker__popup__close').on('click', function(e) {
        e.stopPropagation();
        is_open = false;
        jQuery('.acf-icon-picker__popup-holder').remove();
      });

      jQuery('.acf-icon-picker__popup-holder').on('click', function(e) {
        if(e.target === this){
          e.stopPropagation();
          is_open = false;
          jQuery('.acf-icon-picker__popup-holder').remove();
        }
      });

    });

    // show the remove button if there is an icon selected
    if ($el.find('.icon-value').val().length != 0) {
      $el
        .find('.acf-icon-picker__remove')
        .addClass('acf-icon-picker__remove--active');
    }

    $el.find('.acf-icon-picker__remove').on('click', function(e) {
      e.preventDefault();
      var parent = $(this).parents('.acf-icon-picker');
      parent.find('.icon-value').val('');
      parent
        .find('.acf-icon-picker__icon')
        .html('<span class="acf-icon-picker__icon--span">Ajouter</span>');

      parent
        .find('.acf-icon-picker__remove')
        .removeClass('acf-icon-picker__remove--active');
    });
  }

  if (typeof acf.add_action !== 'undefined') {
    acf.add_action('ready append', function($el) {
      acf.get_fields({ type: 'icon_picker' }, $el).each(function() {
        initialize_field($(this));
      });
    });
  }
})(jQuery);
