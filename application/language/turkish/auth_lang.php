<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Real Estate CMS Pro
*
* Author: Ramazan APAYDIN
*         apaydinweb@gmail.com
*
* Website: http://ramazanapaydin.com
*
* Created:  04.15.2013
*/

// Errors
$lang['error_csrf']                 = 'This form post did not pass our security checks.';
$lang['error_access']               = 'Bu sayfayı görüntüleme yetkiniz yok!';

// Login
$lang['login_heading']              = 'Giriş';
$lang['login_subheading']           = 'Lütfen bir email / kullanıcı adı ve şifreniz ile giriş yapınız.';
$lang['login_identity_label']       = 'E-posta / Kullanıcı Adı:';
$lang['login_password_label']       = 'Parola:';
$lang['login_remember_label']       = 'Beni Hatırla';
$lang['login_submit_btn']           = 'Giriş';
$lang['login_forgot_password']      = 'Şifremi Unuttum?';
$lang['login_singup']               = 'Kayıt Ol';
$lang['login_username']             = 'Email/Username';
$lang['login_password']             = 'Password';

// Index
$lang['index_heading']              = 'Kullanıcılar';
$lang['index_subheading']           = 'Kullanıcıların listesi.';
$lang['index_fname_th']             = 'Ad';
$lang['index_uname_th']             = 'Kullanıcı Adı';
$lang['index_lname_th']             = 'Soyad';
$lang['index_email_th']             = 'E-posta';
$lang['index_groups_th']            = 'Gruplar';
$lang['index_status_th']            = 'Durum';
$lang['index_action_th']            = 'Action';
$lang['index_active_link']          = 'Aktif';
$lang['index_inactive_link']        = 'Pasif';
$lang['index_create_user_link']     = 'Yeni kullanıcı oluştur';
$lang['index_create_group_link']    = 'Yeni grup oluştur';
$lang['index_group']                = 'Gruplar';

// Deactivate User
$lang['deactivate_heading']                     = 'Kullanıcıyı Pasifleştir';
$lang['deactivate_subheading']                  = 'Kullanıcı devre dışı bırakmak istediğinizden emin misiniz \'%s\'';
$lang['deactivate_confirm_y_label']             = 'Evet:';
$lang['deactivate_confirm_n_label']             = 'Hayır:';
$lang['deactivate_submit_btn']                  = 'Gönder';
$lang['deactivate_validation_confirm_label']    = 'Onay';
$lang['deactivate_validation_user_id_label']    = 'user ID';
$lang['deactivate_message']                     = 'Kullanıcı devre dışı bırakıldı.';
$lang['activate_message']                       = 'Kullanıcı etkinleştirildi.';

// Create User
$lang['create_user_heading']                           = 'Kullanıcı Oluştur';
$lang['create_user_subheading']                        = 'Aşağıdaki kullanıcı bilgilerini giriniz.';
$lang['create_user_fname_label']                       = 'Ad:';
$lang['create_user_lname_label']                       = 'Soyad:';
$lang['create_user_company_label']                     = 'Firma Adı:';
$lang['create_user_email_label']                       = 'E-posta:';
$lang['create_user_phone_label']                       = 'Telefon:';
$lang['create_user_password_label']                    = 'Parola:';
$lang['create_user_password_confirm_label']            = 'Parolayı Onayla:';
$lang['create_user_submit_btn']                        = 'Kullanıcı Oluştur';
$lang['create_user_validation_fname_label']            = 'Ad';
$lang['create_user_validation_lname_label']            = 'Soyad';
$lang['create_user_validation_email_label']            = 'E-posta Adresi';
$lang['create_user_validation_username_label']         = 'Kullanıcı Adı';
$lang['create_user_validation_phone1_label']           = 'Telefon Birinci Bölüm';
$lang['create_user_validation_phone2_label']           = 'Telefon İkinci Bölüm';
$lang['create_user_validation_phone3_label']           = 'Telefon Üçüncü Bölüm';
$lang['create_user_validation_company_label']          = 'Firma Adı';
$lang['create_user_validation_password_label']         = 'Parola';
$lang['create_user_validation_password_confirm_label'] = 'Parolayı Onayla';

// Edit User
$lang['edit_user_heading']                           = 'Kullanıcı Düzenle';
$lang['edit_user_subheading']                        = 'Aşağıdaki kullanıcı bilgilerini giriniz.';
$lang['edit_user_fname_label']                       = 'Ad:';
$lang['edit_user_lname_label']                       = 'Soyad:';
$lang['edit_user_company_label']                     = 'Firma Adı:';
$lang['edit_user_email_label']                       = 'E-posta:';
$lang['edit_user_phone_label']                       = 'Telefon:';
$lang['edit_user_password_label']                    = 'Parola:';
$lang['edit_user_password_confirm_label']            = 'Parolayı Onayla:';
$lang['edit_user_groups_heading']                    = 'Grup üyesi';
$lang['edit_user_submit_btn']                        = 'Kullanıcı kaydet';
$lang['edit_user_validation_fname_label']            = 'Ad';
$lang['edit_user_validation_uname_label']            = 'Kullanıcı Adı';
$lang['edit_user_validation_lname_label']            = 'Soyad';
$lang['edit_user_validation_email_label']            = 'E-posta Adresi';
$lang['edit_user_validation_phone1_label']           = 'Telefon Birinci Bölüm';
$lang['edit_user_validation_phone2_label']           = 'Telefon İkinci Bölüm';
$lang['edit_user_validation_phone3_label']           = 'Telefon Üçüncü Bölüm';
$lang['edit_user_validation_company_label']          = 'Firma Adı';
$lang['edit_user_validation_groups_label']           = 'Gruplar';
$lang['edit_user_validation_password_label']         = 'Parola';
$lang['edit_user_validation_password_confirm_label'] = 'Parolayı Onayla';
$lang['edit_user_saved']                             = 'Değişiklikler kaydedildi';
$lang['edit_user_delete']                            = 'Kullanıcı Silindi.';

// Create Group
$lang['create_group_title']                  = 'Grup Oluştur';
$lang['create_group_heading']                = 'Grup Oluştur';
$lang['create_group_subheading']             = 'Aşağıdaki grup bilgilerini giriniz.';
$lang['create_group_name_label']             = 'Grup Adı:';
$lang['create_group_desc_label']             = 'Açıklama:';
$lang['create_group_submit_btn']             = 'Grup Oluştur';
$lang['create_group_validation_name_label']  = 'Grup Adı';
$lang['create_group_validation_desc_label']  = 'Açıklama';
$lang['group_creation_successful']           = 'Grup oluşturuldu';
$lang['group_creat_level']                   = 'Erişim Seviyesi';
$lang['group_creat_name']                    = 'Members adında grup oluşturamazsınız';

// Edit Group
$lang['edit_group_title']                  = 'Grubu Düzenle';
$lang['edit_group_saved']                  = 'Grup Kaydedildi';
$lang['edit_group_heading']                = 'Grubu Düzenle';
$lang['edit_group_subheading']             = 'Aşağıdaki grup bilgilerini giriniz.';
$lang['edit_group_name_label']             = 'Grup Adı:';
$lang['edit_group_desc_label']             = 'Açıklama:';
$lang['edit_group_submit_btn']             = 'Grubu Kaydet';
$lang['edit_group_validation_name_label']  = 'Grup Adı';
$lang['edit_group_validation_desc_label']  = 'Açıklama';
$lang['edit_group_validation_members']	   = 'Members grubunu düzenleyemezsiniz.';
$lang['all_group_id']			   = 'ID';
$lang['all_group_name']	   		   = 'Grup Adı';
$lang['all_group_description']	   	   = 'Açıklama';
$lang['all_group_access']		   = 'Erişim İzinleri';
$lang['all_group_action']		   = 'İşlemler';
$lang['all_group_delete']		   = 'Grup Silindi.';
$lang['all_group_notdelete']		   = 'Ön tanımlı gruplar silinemez.';

// Change Password
$lang['change_password_heading']                               = 'Parolayı Değiştir';
$lang['change_password_old_password_label']                    = 'Eski Parola:';
$lang['change_password_new_password_label']                    = 'Yeni Parola:';
$lang['change_password_new_password_confirm_label']            = 'Parolayı Onayla:';
$lang['change_password_submit_btn']                            = 'Değiştir';
$lang['change_password_validation_old_password_label']         = 'Eski Parola';
$lang['change_password_validation_new_password_label']         = 'Yeni Parola';
$lang['change_password_validation_new_password_confirm_label'] = 'Parolayı Onayla';

// Forgot Password
$lang['forgot_password_heading']                 = 'Şifremi Unuttum';
$lang['forgot_password_subheading']              = 'Şifrenizi sıfırlamak için aşağıya e-posta adresinizi girin.';
$lang['forgot_password_email_label']             = '%s:';
$lang['forgot_password_submit_btn']              = 'Gönder';
$lang['forgot_password_validation_email_label']  = 'E-posta Adresi';
$lang['forgot_password_username_identity_label'] = 'Kullanıcı Adı';
$lang['forgot_password_email_identity_label']    = 'E-posta';


// Reset Password
$lang['reset_password_heading']                               = 'Şifre Değiştir';
$lang['reset_password_new_password_label']                    = 'Yeni Parola:';
$lang['reset_password_new_password_confirm_label']            = 'Parolayı Onayla:';
$lang['reset_password_submit_btn']                            = 'Değiştir';
$lang['reset_password_validation_new_password_label']         = 'Yeni Parola';
$lang['reset_password_validation_new_password_confirm_label'] = 'Parolayı Onayla';

// Activation Email
$lang['email_activate_heading']             = '%s için hesap etkinleştirme';
$lang['email_activate_subheading']          = 'Bu linki tıklayınız %s.';
$lang['email_activate_link']                = 'Hesabınızı Etkinleştirin';

// Forgot Password Email
$lang['email_forgot_password_heading']      = '%s için Parola Sıfırlama';
$lang['email_forgot_password_subheading']   = 'Bu linki tıklayınız %s.';
$lang['email_forgot_password_link']         = 'Şifrenizi sıfırlayın';

// New Password Email
$lang['email_new_password_heading']         = '%s için yeni Şifre';
$lang['email_new_password_subheading']      = 'Parolanız sıfırlanmıştır: %s';

//Other
$lang['edit'] 					= 'Emlak Düzenle';
$lang['delete'] 				= 'Emlak Sil';
$lang['activate'] 				= 'Emlak Aktif/Pasif';
$lang['edit_user_profile']                      = 'Profil';

