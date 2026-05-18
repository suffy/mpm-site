<style>
#form {
  max-height: 0;
  opacity: 0;
  overflow: hidden;
  transition:
    max-height 0.5s ease,
    opacity 0.5s ease;
}
#form.show {
  max-height: 1000px; /* atau nilai yang cukup besar */
  opacity: 1;
  transition: all 0.15s ease-in-out;
  margin-top: 1rem;
  margin-bottom: 1rem;
}
#form.show #divform2 {
  opacity: 1;
  max-height: none;
  transition: opacity 0.5s ease;
}
#form:not(.show) #divform2 {
  opacity: 0;
  max-height: 0;
  overflow: hidden;
}
/* // ubah ukuran font di tabel */
table td,
table th {
  padding: 0.3rem;
  font-size: 12px;
  font-weight: bold;
}
.font-btn {
  font-size: 11px;
  padding: 0.3rem;
  font-weight: bold;
}
</style>